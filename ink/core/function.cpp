#include <stdlib.h>
#include "error.h"
#include "object.h"
#include "expression.h"
#include "gc/collect.h"
#include "interface/engine.h"

namespace ink {

inline Ink_FunctionAttribution getFuncAttr(Ink_Object *obj)
{
	return as<Ink_FunctionObject>(obj)->attr;
}

inline void setFuncAttr(Ink_Object *obj, Ink_FunctionAttribution attr)
{
	as<Ink_FunctionObject>(obj)->setAttr(attr);
	return;
}

inline Ink_Object *callWithAttr(Ink_Object *obj, Ink_FunctionAttribution attr,
								Ink_InterpreteEngine *engine, Ink_ContextChain *context,
								Ink_Object *base = NULL, Ink_ArgcType argc = 0, Ink_Object **argv = NULL)
{
	Ink_FunctionAttribution attr_back;
	Ink_Object *ret = NULL_OBJ;

	if (obj->type == INK_FUNCTION) {
		attr_back = getFuncAttr(obj);
		setFuncAttr(obj, attr);
		ret = obj->call(engine, context, base, argc, argv);
		setFuncAttr(obj, attr_back);
	}
	return ret;
}

Ink_Object *Ink_Object::triggerCallEvent(Ink_InterpreteEngine *engine, Ink_ContextChain *context,
										 Ink_ArgcType argc, Ink_Object **argv)
{
	Ink_Object *at_call;
	Ink_Object *ret = NULL;

	if ((at_call = getSlot(engine, "@call", false))->type == INK_FUNCTION) {
		// at_call->setSlot_c("base", this);
		ret = at_call->call(engine, context, this, argc, argv);
	}

	return ret;
}

Ink_Object **Ink_FunctionObject::copyDeepArgv(Ink_InterpreteEngine *engine,
											  Ink_ArgcType argc, Ink_Object **argv)
{
	Ink_Object **ret = (Ink_Object **)malloc(sizeof(Ink_Object *) * argc);
	Ink_ArgcType i;

	for (i = 0; i < argc; i++) {
		ret[i] = argv[i]->cloneDeep(engine);
	}

	return ret;
}

void Ink_FunctionObject::triggerInterruptEvent(Ink_InterpreteEngine *engine, Ink_ContextChain *context,
											   Ink_ContextObject *local, Ink_Object *receiver)
{
	Ink_Object *tmp = NULL;
	Ink_Object **tmp_argv = NULL;
	Ink_InterruptSignal signal_backup = engine->getSignal();
	Ink_Object *value_backup
				= local->setReturnVal(engine->getInterruptValue());
				/* set return value of context object for GC to mark */
	string *tmp_str = NULL;
	bool event_found = false;

	tmp_argv = (Ink_Object **)malloc(sizeof(Ink_Object *));
	engine->setInterrupt(INTER_NONE, NULL);
	if (signal_backup < INTER_LAST) {
		if ((tmp = receiver->getSlot(engine, (string("@") + Ink_InterpreteEngine::getNativeSignalName(signal_backup)).c_str(), false))
			->type == INK_FUNCTION) {
			tmp_argv[0] = value_backup;
			event_found = true;
			// tmp->setSlot_c("base", receiver);
			callWithAttr(tmp, Ink_FunctionAttribution(INTER_NONE), engine, context, receiver, 1, tmp_argv);
		}
	} else if (signal_backup > INTER_LAST) {
		// custom signal
		tmp_str = engine->getCustomInterruptSignalName(signal_backup);
		if (tmp_str) {
			if ((tmp = receiver->getSlot(engine, (string("@") + *tmp_str).c_str(), false))
				->type == INK_FUNCTION) {
				tmp_argv[0] = value_backup;
				event_found = true;
				// tmp->setSlot_c("base", receiver);
				callWithAttr(tmp, Ink_FunctionAttribution(INTER_NONE), engine, context, receiver, 1, tmp_argv);
			}
		} else {
			InkWarn_Unregistered_Interrupt_Signal(engine, signal_backup);
		}
	} else {
		InkWarn_Unregistered_Interrupt_Signal(engine, signal_backup);
	}
	free(tmp_argv);

	/* restore signal if no event found */
	if (!event_found) {
		engine->setInterrupt(signal_backup, value_backup);
	}
	
	return;
}

Ink_Object *Ink_FunctionObject::checkUnkownArgument(Ink_Object *&base, Ink_ArgcType &argc, Ink_Object **&argv,
													Ink_Object *&this_p, bool &if_return_this,
													bool &if_delete_argv)
{
	Ink_ArgcType tmp_argc, j, argi;
	Ink_Object **tmp_argv;
	Ink_Object *ret_val = NULL;
	bool is_arg_completed = true;

	/* if some arguments have been applied already */
	if (pa_argv) {
		tmp_argc = pa_argc;
		tmp_argv = copyArgv(pa_argc, pa_argv);

		for (j = 0, argi = 0; j < tmp_argc; j++) {
			/* find unknown place to put in arguments */
			if (isUnknown(tmp_argv[j])) {
				if (argi < argc /* not excess */
					&& !isUnknown(argv[argi]) /* not another unknown argument */)
					tmp_argv[j] = argv[argi];
				else
					is_arg_completed = false;
				argi++;
			}
		}

		if (!is_arg_completed) {
			/* still missing arguments -- return another PAF */
			if (argi < argc) {
				Ink_ArgcType remainc = argc - argi; /* remaining arguments */
				argc = remainc + tmp_argc;
				/* link the PA arguments and remaining arguments */
				argv = linkArgv(tmp_argc, tmp_argv,
								remainc, &argv[argi]);

				free(tmp_argv);
				tmp_argc = argc;
				tmp_argv = argv;
			}
			ret_val = cloneWithPA(engine, base, tmp_argc, tmp_argv,
								  this_p, if_return_this, true);
			goto RETURN;
		}

		Ink_ArgcType remainc = argc - argi; /* remaining arguments */
		argc = remainc + tmp_argc;
		/* link the PA arguments and remaining arguments */
		argv = linkArgv(tmp_argc, tmp_argv,
						remainc, &argv[argi]);
		free(tmp_argv);
		if_delete_argv = true;
	}

	for (argi = 0; argi < argc; argi++) {
		if (isUnknown(argv[argi])) { /* find unknown argument */
			ret_val = cloneWithPA(engine, base, argc, copyArgv(argc, argv),
								  this_p, if_return_this, true);
			goto RETURN;
		}
	}

RETURN:

	if (!ret_val && is_pa) {
		base = pa_info_base_p;
		this_p = pa_info_this_p;
		if_return_this = pa_info_if_return_this;
	}

	return ret_val;
}

Ink_Object *Ink_FunctionObject::call(Ink_InterpreteEngine *engine, Ink_ContextChain *context,
									 Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv,
									 Ink_Object *this_p, bool if_return_this)
{
	Ink_ExpressionList::size_type i;
	Ink_ArgcType argi;
	Ink_ParamList::size_type j;
	Ink_ContextObject *local;
	Ink_Object *ret_val = NULL, *pa_ret = NULL;
	Ink_Array *var_arg = NULL;
	IGC_CollectEngine *gc_engine_backup = engine->getCurrentGC();
#if 1
	char *this_debug_name_back = getDebugName() ? strdup(getDebugName()) : NULL;
#endif

	bool force_return = false;
	bool if_delete_argv = false;

	/* if not inline function, set local context */
	bool if_set_sp_ptr = !is_inline;

#if 1
	ret_val = triggerCallEvent(engine, context, argc, argv);
	if (engine->getSignal() != INTER_NONE) {
		/* interrupt event triggered */
		triggerInterruptEvent(engine, context, context->getLocal(), this);

		if (engine->getSignal() != INTER_NONE) {
			/* whether trap the signal */
			if (attr.hasTrap(engine->getSignal())) {
				ret_val = engine->trapSignal();
			} else {
				ret_val = engine->getInterruptValue();
			}
			free(this_debug_name_back);
			return ret_val ? ret_val : NULL_OBJ;
		}
	}

	ret_val = NULL;

	if ((pa_ret = checkUnkownArgument(base, argc, argv, this_p,
									  if_return_this, if_delete_argv))
		!= NULL) {
		if (if_delete_argv)
			free(argv);

		free(this_debug_name_back);
		return pa_ret;
	}
#endif

	/* init GC engine */
	IGC_CollectEngine *gc_engine = new IGC_CollectEngine(engine);
	engine->setCurrentGC(gc_engine);

	if (closure_context) {
		context = closure_context->copyContextChain(); /* copy closure context chain */
	} else {
		context = context->copyContextChain();
	}

	/* create new local context */
	if (is_ref) {
		local = context->getLocal();
	} else {
		local = new Ink_ContextObject(engine);
		context->addContext(local);
	}

	if (!is_ref) {
		if (if_set_sp_ptr) {
			// local->setSlot_c("base", getSlot(engine, "base"));
			local->setSlot_c("base", base);
			local->setSlot_c("this", this);
		} else if (is_native) {
			local->setSlot_c("base", base);
		}

		local->setSlot_c("self", this);
		local->setSlot_c("let", local);

		/* set "this" pointer if exists */
		if (this_p)
			local->setSlot_c("this", this_p);
	}

#if 1
	setDebugName(this_debug_name_back);
	free(this_debug_name_back);

	/* set trace(unsed for mark&sweep GC) and set debug info */
	if (!is_ref) {
		engine->addTrace(local)->setDebug(engine->current_file_name,
										  engine->current_line_number, this);
	}
#endif
	/* set local context */
	// gc_engine->initContext(context);

	if (is_native) {
		/* if it's a native function, call the function pointer */
		ret_val = native(engine, context, base, argc, argv, this_p);
		/* interrupt signal received */
		if (engine->getSignal() != INTER_NONE) {
			/* interrupt event triggered */
			triggerInterruptEvent(engine, context, local, this);

			if (engine->getSignal() != INTER_NONE)
				goto SIGINT_START;
		}
	} else {
		/* create local variable according to the parameter list */
		for (j = 0, argi = 0; j < param.size(); j++, argi++) {
			if (param[j].is_variant) { /* find variant argument -- break the loop */
				break;
			}
			local->setSlot(param[j].name->c_str(),
						   argi < argc ? argv[argi]
						   			   : UNDEFINED); // initiate local argument
		}

		if (j < param.size() && param[j].is_variant) {
			/* breaked from finding variant arguments */

			/* create variant arguments */
			var_arg = new Ink_Array(engine);
			for (; argi < argc; argi++) {
				/* push arguments in to VA array */
				var_arg->value.push_back(new Ink_HashTable(argv[argi], var_arg));
			}

			/* set VA array */
			local->setSlot(param[j].name->c_str(), var_arg);
		}

		if (argi < argc) { /* still some parameter remaining */
			InkNote_Exceed_Argument(engine);
		}

		for (i = 0; i < exp_list.size(); i++) {
			gc_engine->checkGC();
			ret_val = exp_list[i]->eval(engine, context); // eval each expression

			/* interrupt signal received */
			if (engine->getSignal() != INTER_NONE) {
				/* interrupt event triggered */
				triggerInterruptEvent(engine, context, local, this);

				if (engine->getSignal() == INTER_NONE)
					continue;
				goto SIGINT_START;
			}
		}
	}

goto SIGINT_END;
SIGINT_START:

/* whether trap the signal */
if (attr.hasTrap(engine->getSignal())) {
	ret_val = engine->trapSignal();
} else {
	ret_val = engine->getInterruptValue();
}
force_return = true;

SIGINT_END:

	/* conditions of returning "this" pointer:
	 * 1. has "this" pointer as argument
	 * 2. bool if_return_this is true(in default)
	 * 3. no force return
	 */

	if (this_p && if_return_this && !force_return) {
		ret_val = local->getSlot(engine, "this", false);
	}

	if (if_delete_argv)
		free(argv);

	/* remove local context from chain and trace(if not reference) */
	if (!is_ref) {
		context->removeLast();
		engine->removeTrace(local);
	}
	
	/* mark return value before sweeping */
	if (ret_val) {
		engine->setGlobalReturnValue(ret_val);
		// ret_val->setBase(NULL);
		// gc_engine->doMark(ret_val);
	}

	// gc_engine->collectGarbage();
	gc_engine->checkGC();

	/* dispose context chain copied */
	Ink_ContextChain::disposeContextChain(context);

	/* link remaining objects to previous GC engine */
	if (engine->coro_tmp_engine) engine->coro_tmp_engine->link(gc_engine);
	else if (gc_engine_backup) {
		gc_engine_backup->link(gc_engine);
	}

	/* restore GC engine */
	engine->setCurrentGC(gc_engine_backup);
	engine->setGlobalReturnValue(NULL);
	delete gc_engine;

	return ret_val ? ret_val : NULL_OBJ; // return the last expression
}

Ink_Object *Ink_FunctionObject::clone(Ink_InterpreteEngine *engine)
{
	Ink_FunctionObject *new_obj = new Ink_FunctionObject(engine);
	
	new_obj->is_native = is_native;
	new_obj->is_inline = is_inline;
	new_obj->is_ref = is_ref;
	new_obj->native = native;

	new_obj->param = param;
	new_obj->exp_list = exp_list;
	if (closure_context)
		new_obj->closure_context = closure_context->copyContextChain();
	new_obj->attr = attr;

	new_obj->is_pa = is_pa;
	new_obj->pa_argc = pa_argc;
	new_obj->pa_argv = copyArgv(pa_argc, pa_argv);
	new_obj->pa_info_base_p = pa_info_base_p;
	new_obj->pa_info_this_p = pa_info_this_p;
	new_obj->pa_info_if_return_this = pa_info_if_return_this;

	cloneHashTable(this, new_obj);

	return new_obj;
}

Ink_Object *Ink_FunctionObject::cloneDeep(Ink_InterpreteEngine *engine)
{
	Ink_FunctionObject *new_obj;
	Ink_Object *tmp;

	if (!(tmp = engine->cloneDeepHasTraced(this))) {
		new_obj = new Ink_FunctionObject(engine);
		engine->addDeepCloneTrace(this, new_obj);
		
		new_obj->is_native = is_native;
		new_obj->is_inline = is_inline;
		new_obj->is_ref = is_ref;
		new_obj->native = native;

		new_obj->param = param;
		new_obj->exp_list = exp_list;
		if (closure_context) {
			new_obj->closure_context = closure_context->copyDeepContextChain(engine);
		}
		new_obj->attr = attr;

		new_obj->is_pa = is_pa;
		new_obj->pa_argc = pa_argc;
		new_obj->pa_argv = copyDeepArgv(engine, pa_argc, pa_argv);
		new_obj->pa_info_base_p = pa_info_base_p ? pa_info_base_p->cloneDeep(engine) : NULL;
		new_obj->pa_info_this_p = pa_info_this_p ? pa_info_this_p->cloneDeep(engine) : NULL;
		new_obj->pa_info_if_return_this = pa_info_if_return_this;

		cloneDeepHashTable(engine, this, new_obj);
	} else return tmp;

	return new_obj;
}

void Ink_FunctionObject::doSelfMark(Ink_InterpreteEngine *engine, IGC_Marker marker)
{
	Ink_ArgcType argi;

	if (closure_context) {
		closure_context->doSelfMark(engine, marker);
	}

	if (pa_argv) {
		for (argi = 0; argi < pa_argc; argi++) {
			marker(engine, pa_argv[argi]);
		}
	}

	if (pa_info_base_p)
		marker(engine, pa_info_base_p);

	if (pa_info_this_p)
		marker(engine, pa_info_this_p);

	return;
}

Ink_Object *Ink_Object::call(Ink_InterpreteEngine *engine, Ink_ContextChain *context,
							 Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv,
							 Ink_Object *this_p, bool if_return_this)
{
	Ink_Object *ret;

	if ((ret = triggerCallEvent(engine, context, argc, argv)) == NULL) {
		ret = NULL_OBJ;
		InkError_Calling_Non_Function_Object(engine, type, getDebugName());
	}

	return ret;
}

Ink_Object *Ink_Undefined::call(Ink_InterpreteEngine *engine, Ink_ContextChain *context,
								Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv,
								Ink_Object *this_p, bool if_return_this)
{
	InkError_Calling_Undefined_Object(engine, getDebugName());
	return NULL_OBJ;
}

Ink_FunctionObject::~Ink_FunctionObject()
{
	if (closure_context) Ink_ContextChain::disposeContextChain(closure_context);
	if (pa_argv) free(pa_argv);
	cleanHashTable();
}

}
