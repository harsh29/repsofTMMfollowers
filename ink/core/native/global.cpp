#include <stdio.h>
#include "native.h"
#include "../object.h"
#include "../context.h"
#include "../expression.h"
#include "../error.h"
#include "../debug.h"
#include "../general.h"
#include "../syntax/syntax.h"
#include "../gc/collect.h"
#include "../interface/engine.h"
#include "../interface/setting.h"
#include "../package/load.h"
#include "../coroutine/coroutine.h"

namespace ink {

bool isTrue(Ink_Object *cond)
{
	return cond && cond->isTrue();
}

static Ink_Object *Ink_ArrayConstructor(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_ContextObject *local = context->getLocal();
	Ink_Array *ret;

	if (argc) {
		if (argv[0]->type == INK_NUMERIC && (argc == 1 || argc == 2)) {
			if (argc == 1) {
				ret = new Ink_Array(engine, Ink_ArrayValue((Ink_SizeType)getInt(as<Ink_Numeric>(argv[0])->getValue()), NULL));
			} else {
				Ink_ArrayValue::size_type i;
				ret = new Ink_Array(engine, Ink_ArrayValue((Ink_SizeType)getInt(as<Ink_Numeric>(argv[0])->getValue()), NULL));

				for (i = 0; i < ret->value.size(); i++) {
					ret->value[i] = new Ink_HashTable(argv[1], ret);
				}
			}
		} else if (argv[0]->type == INK_ARRAY && argc == 1) {
			ret = new Ink_Array(engine);
			ret->value = Ink_Array::cloneArrayValue(as<Ink_Array>(argv[0])->value, ret);
		} else {
			Ink_ArrayValue::size_type i;
			ret = new Ink_Array(engine);

			for (i = 0; i < argc; i++) {
				ret->value.push_back(new Ink_HashTable(argv[i], ret));
			}
		}
	} else {
		ret = new Ink_Array(engine);
	}

	local->setSlot_c("this", ret);

	return ret;
}

static Ink_Object *Ink_Eval(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_Object *ret = NULL_OBJ;
	Ink_ExpressionList top_level_backup;
	const char *file_name_backup = engine->getFilePath();
	string *new_file_name;
	stringstream strm;

	if (!checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return ret;
	}

	InkParser_setParserInfo(1, "from eval: ");

	top_level_backup = engine->top_level;

	strm << file_name_backup << ": eval in line " << engine->current_line_number;
	new_file_name = new string(strm.str());
	engine->setFilePath(new_file_name->c_str());

	context->removeLast();

	engine->startParse(as<Ink_String>(argv[0])->getValue());
	ret = engine->execute(context, false);

	Ink_insertNativeExpression(engine->top_level.begin(),
							   engine->top_level.end());
	engine->top_level = top_level_backup;

	context->addContext(new Ink_ContextObject(engine));

	delete new_file_name;
	engine->setFilePath(file_name_backup);

	return ret;
}

bool defined(Ink_Object *obj)
{
	return obj->type != INK_UNDEFINED;
}

static Ink_Object *Ink_Print(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	if (!checkArgument(engine, argc, 1)) {
		return NULL_OBJ;
	}
	string tmp_str;

	if (argv[0]->type == INK_NUMERIC)
		printf("print(numeric): %s\n", as<Ink_Numeric>(argv[0])->getValue().toString().c_str());
	else if (argv[0]->type == INK_STRING) {
		tmp_str = as<Ink_String>(argv[0])->getValue();
		printf("%s\n", tmp_str.c_str());
	}
	else if (argv[0]->type == INK_NULL)
		printf("(null)\n");
	else if (argv[0]->type == INK_UNDEFINED)
		printf("(undefined)\n");
	else
		printf("print: non-printable type: %ld\n", argv[0]->type);

	return NULL_OBJ;
}

static Ink_SizeType import_path_count = 0;
static char **import_path = NULL;

void Ink_addImportPath(const char *path)
{
	import_path = (char **)realloc(import_path, sizeof(char *) * ++import_path_count);
	import_path[import_path_count - 1] = strdup(path);
	return;
}

static Ink_Object *Ink_Import(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_ArgcType i;
	Ink_SizeType j;
	FILE *fp;
	Ink_Object *load, **tmp_argv;
	Ink_ExpressionList top_level_backup;

	char *current_dir = NULL, *redirect = NULL;
	const char *file_name_backup;
	string *tmp;
	string *full_file_name;
	string tmp_path;

	for (i = 0; i < argc; i++) {
		if (argv[i]->type == INK_STRING) {
			/* run a source file */

			tmp = new string(as<Ink_String>(argv[i])->getValue());
			current_dir = getCurrentDir();

			if (!(fp = fopen(tmp->c_str(), "r"))) {
				tmp_path = string(INK_MODULE_DIR) + INK_PATH_SPLIT + tmp->c_str();

				/* cannot found in current dir, search module dir */
				if (!(fp = fopen(tmp_path.c_str(), "r"))) {

					/* cannot found in module dir, search in other import paths */
					for (j = 0; j < import_path_count; j++) {
						tmp_path = string(import_path[j]) + INK_PATH_SPLIT + tmp->c_str();
						if ((fp = fopen(tmp_path.c_str(), "r")) != NULL) {
							break;
						}
					}

					if (!fp) {
						InkError_Failed_Open_File(engine, tmp->c_str());
						free(current_dir);
						delete tmp;
						continue;
					}
				}
				full_file_name = new string(tmp_path);
			} else {
				full_file_name = new string(string(current_dir) + INK_PATH_SPLIT + string(tmp->c_str()));
			}

			/* change dir to the dest dir */
			redirect = getBasePath(full_file_name->c_str());
			if (redirect) {
				changeDir(redirect);
				free(redirect);
			}

			/* backup file name, yacc prefix & lineno and set new */
			file_name_backup = engine->getFilePath();
			engine->setFilePath(full_file_name->c_str());
			InkParser_setParserInfo(1, "from import: ");

			/* remove the last context created for import itself */
			context->removeLast();

			/* backup original top level backup */
			top_level_backup = engine->top_level;

			/* parse and execute */
			engine->startParse(fp);
			engine->execute(context);

			/* store native expressions */
			Ink_insertNativeExpression(engine->top_level.begin(),
									   engine->top_level.end());

			/* restore original top level */
			engine->top_level = top_level_backup;

			/* recreate local context */
			context->addContext(new Ink_ContextObject(engine));

			/* a few clean steps */
			fclose(fp);
			if (current_dir) {
				changeDir(current_dir);
				free(current_dir);
			}
			engine->setFilePath(file_name_backup);

			delete full_file_name;
			delete tmp;
		} else {
			/* call load function of package object */

			if ((load = getSlotWithProto(engine, context, argv[i], "load"))->type == INK_FUNCTION) {
				context->removeLast();
				tmp_argv = (Ink_Object **)malloc(sizeof(Ink_Object *) * 2);
				tmp_argv[0] = argv[i];
				tmp_argv[1] = context->getLocal();
				load->call(engine, context, argv[i], 2, tmp_argv);
				free(tmp_argv);
				context->addContext(new Ink_ContextObject(engine));
			} else {
				InkWarn_Not_Package(engine);
			}
		}
	}

	return NULL_OBJ;
}

Ink_Object *InkNative_Import_i(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	return Ink_Import(engine, context, base, argc, argv, this_p);
}

static Ink_Object *Ink_TypeName(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	if (argc < 1) {
		InkWarn_Type_Name_Argument_Require(engine);
		return NULL_OBJ;
	}

	return new Ink_String(engine, engine->getTypeName(argv[0]->type));
}

static Ink_Object *Ink_IntVal(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_NumericValue tmp;
	bool is_success = false;

	if (!checkArgument(false, argc, argv, 1, INK_NUMERIC)
		&& !checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return NULL_OBJ;
	}

	if (argv[0]->type == INK_NUMERIC) {
		return new Ink_Numeric(engine, getFloat(as<Ink_Numeric>(argv[0])->getValue()));
	}

	string tmp_str = as<Ink_String>(argv[0])->getValue();

	if (tmp_str == "") {
		return NULL_OBJ;
	}

	tmp = Ink_NumericExpression::parseInt(tmp_str, false, &is_success);

	if (!is_success)
		return NULL_OBJ;

	return new Ink_Numeric(engine, tmp);
}

static Ink_Object *Ink_NumVal(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_NumericValue tmp;
	bool is_success = false;

	if (!checkArgument(false, argc, argv, 1, INK_NUMERIC)
		&& !checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return NULL_OBJ;
	}

	if (argv[0]->type == INK_NUMERIC) {
		return new Ink_Numeric(engine, as<Ink_Numeric>(argv[0])->getValue());
	}

	string tmp_str = as<Ink_String>(argv[0])->getValue();

	if (tmp_str == "") {
		return NULL_OBJ;
	}

	tmp = Ink_NumericExpression::parseInt(tmp_str, true, &is_success);
	
	if (!is_success) {
		tmp = Ink_NumericExpression::parseFloat(tmp_str, false, &is_success);
		if (!is_success) {
			return NULL_OBJ;
		}
	}

	return new Ink_Numeric(engine, tmp);
}

static Ink_Object *Ink_UnicodeToString(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	if (!checkArgument(engine, argc, argv, 1, INK_NUMERIC)) {
		return NULL_OBJ;
	}

	wchar_t chr = getInt(argv[0]);

	return new Ink_String(engine, wstring(&chr, 1));
}

static Ink_Object *Ink_Debug(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_ArgcType i;
	if (!checkArgument(engine, argc, 1))
		return NULL_OBJ;

	for (i = 0; i < argc; i++) {
		engine->initPrintDebugInfo();
		engine->printDebugInfo(stderr, argv[i]);
	}

	return NULL_OBJ;
}

static Ink_Object *Ink_Where(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	engine->printTrace(stderr, engine->getTrace());
	return NULL_OBJ;
}

static Ink_Object *Ink_CoroutineCall(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_CoCallList co_call_list = Ink_CoCallList();
	Ink_ArgcType i;
	Ink_CoCallList::size_type j;
	Ink_Object **tmp_argv;
	Ink_Object *ret_val;

	for (i = 0; i < argc; i += 2) {
		if (argv[i]->type != INK_FUNCTION || argv[i + 1]->type != INK_ARRAY) {
			InkWarn_Cocall_Argument_Require(engine);
			ret_val = NULL_OBJ;
			goto END;
		}
		tmp_argv = arrayValueToObjects(as<Ink_Array>(argv[i + 1])->value);
		co_call_list.push_back(Ink_CoCall(as<Ink_FunctionObject>(argv[i]),
										  as<Ink_Array>(argv[i + 1])->value.size(),
										  tmp_argv));
	}

	ret_val = InkCoCall_call(engine, context, co_call_list);

END:
	for (j = 0; j < co_call_list.size(); j++) {
		free(co_call_list[j].argv);
	}

	return ret_val;
}

static Ink_Object *Ink_Auto_Missing(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	string tmp_str;

	if (!checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return UNDEFINED;
	}

	tmp_str = as<Ink_String>(argv[0])->getValue();

	return Ink_IdentifierExpression::getContextSlot(engine, context, tmp_str.c_str(),
													Ink_EvalFlag(), false);
}

Ink_Object *InkNative_Auto_Missing_i(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	return Ink_Auto_Missing(engine, context, base, argc, argv, this_p);
}

static Ink_Object *Ink_Fix_Assign(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_Object *ret;

	if (!checkArgument(engine, argc, argv, 2, INK_STRING)) {
		return UNDEFINED;
	}

	wstring wname = as<Ink_String>(argv[0])->getWValue();
	
	if ((ret = engine->findConstant(wname)) != NULL) {
		char *tmp = Ink_wcstombs_alloc(wname.c_str());
		InkWarn_Assign_Fixed(engine, tmp);
		free(tmp);
	} else {
		engine->setConstant(wname, ret = argv[1]);
	}

	return ret;
}

static Ink_Object *Ink_Fix_Missing(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	wstring tmp_wstr;
	Ink_Object *ret;
	Ink_FunctionObject *tmp_func;

	if (!checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return UNDEFINED;
	}

	tmp_wstr = as<Ink_String>(argv[0])->getWValue();

	if ((ret = engine->findConstant(tmp_wstr)) != NULL) {
		ret->address = NULL;
	} else {
		ret = UNDEFINED;
		ret->setSlot_c("=", tmp_func = new Ink_FunctionObject(engine, Ink_Fix_Assign));
		tmp_func->pa_argc = 2;
		tmp_func->pa_argv = (Ink_Object **)malloc(sizeof(Ink_Object *) * 2);
		tmp_func->pa_argv[0] = new Ink_String(engine, tmp_wstr);
		tmp_func->pa_argv[1] = new Ink_Unknown(engine);
	}

	return ret;
}

Ink_Object *InkNative_Fix_Missing_i(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	return Ink_Fix_Missing(engine, context, base, argc, argv, this_p);
}

static Ink_Object *Ink_Abort(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_disposeEnv();
	abort();
	return NULL_OBJ;
}

static Ink_Object *Ink_RegisterSignal(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	if (!checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return NULL_OBJ;
	}

	engine->addCustomInterruptSignal(as<Ink_String>(argv[0])->getValue());

	return NULL_OBJ;
}

static Ink_Object *Ink_DeleteSignal(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	if (!checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return NULL_OBJ;
	}

	return new Ink_Numeric(engine, engine->deleteCustomInterruptSignal(as<Ink_String>(argv[0])->getValue()));
}

static Ink_Object *Ink_SetErrorMode(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	if (!checkArgument(engine, argc, argv, 1, INK_STRING)) {
		return NULL_OBJ;
	}

	string mode = as<Ink_String>(argv[0])->getValue();

	if (mode == "strict") {
		engine->setErrorMode(INK_ERRMODE_STRICT);
	} else if (mode == "default") {
		engine->setErrorMode(INK_ERRMODE_DEFAULT);
	} else {
		return NULL_OBJ;
	}

	return TRUE_OBJ;
}

static Ink_Object *Ink_GetErrorMode(Ink_InterpreteEngine *engine, Ink_ContextChain *context, Ink_Object *base, Ink_ArgcType argc, Ink_Object **argv, Ink_Object *this_p)
{
	Ink_ErrorMode mode = engine->getErrorMode();

	switch (mode) {
		case INK_ERRMODE_STRICT:
			return new Ink_String(engine, "strict");
		case INK_ERRMODE_DEFAULT:
			return new Ink_String(engine, "default");
	}

	return new Ink_String(engine, "unknown");
}

static Ink_ErrnoMap ink_errno_map[] = {
	{ "UNDEFINED", INK_CORE_MOD_ID, INK_EXCODE_UNDEFINED},

	{ "CALLING_NON_FUNCTION_OBJECT", INK_CORE_MOD_ID, INK_EXCODE_ERROR_CALLING_NON_FUNCTION_OBJECT },
	{ "CALLING_UNDEFINED_OBJECT", INK_CORE_MOD_ID, INK_EXCODE_ERROR_CALLING_UNDEFINED_OBJECT },
	{ "YIELD_WITHOUT_COROUTINE", INK_CORE_MOD_ID, INK_EXCODE_ERROR_YIELD_WITHOUT_COROUTINE },
	{ "FAILED_OPEN_FILE", INK_CORE_MOD_ID, INK_EXCODE_ERROR_FAILED_OPEN_FILE },

	{ "ASSIGNING_UNASSIGNABLE_EXPRESSION", INK_CORE_MOD_ID, INK_EXCODE_WARN_ASSIGNING_UNASSIGNABLE_EXPRESSION },
	{ "HASH_NOT_FOUND", INK_CORE_MOD_ID, INK_EXCODE_WARN_HASH_NOT_FOUND },
	{ "GET_SLOT_OF_UNDEFINED", INK_CORE_MOD_ID, INK_EXCODE_WARN_GET_SLOT_OF_UNDEFINED },
	{ "INSERT_NON_FUNCTION_OBJECT", INK_CORE_MOD_ID, INK_EXCODE_WARN_INSERT_NON_FUNCTION_OBJECT },
	{ "ARRAY_INDEX_EXCEED", INK_CORE_MOD_ID, INK_EXCODE_WARN_ARRAY_INDEX_EXCEED },
	{ "BONDING_FAILED", INK_CORE_MOD_ID, INK_EXCODE_WARN_BONDING_FAILED },
	{ "SELF_BONDING", INK_CORE_MOD_ID, INK_EXCODE_WARN_SELF_BONDING },
	{ "GET_NON_ARRAY_INDEX", INK_CORE_MOD_ID, INK_EXCODE_WARN_GET_NON_ARRAY_INDEX },
	{ "FUNCTION_RANGE_CALL_ARGUMENT_ERROR", INK_CORE_MOD_ID, INK_EXCODE_WARN_FUNCTION_RANGE_CALL_ARGUMENT_ERROR },
	{ "FUNCTION_NON_RANGE_CALL", INK_CORE_MOD_ID, INK_EXCODE_WARN_FUNCTION_NON_RANGE_CALL },
	{ "INCORRECT_RANGE_TYPE", INK_CORE_MOD_ID, INK_EXCODE_WARN_INCORRECT_RANGE_TYPE },
	{ "GET_NON_FUNCTION_EXP", INK_CORE_MOD_ID, INK_EXCODE_WARN_GET_NON_FUNCTION_EXP },
	{ "REBUILD_NON_ARRAY", INK_CORE_MOD_ID, INK_EXCODE_WARN_REBUILD_NON_ARRAY },
	{ "INVALID_ELEMENT_FOR_REBUILD", INK_CORE_MOD_ID, INK_EXCODE_WARN_INVALID_ELEMENT_FOR_REBUILD },
	{ "REMOVE_ARGUMENT_REQUIRE", INK_CORE_MOD_ID, INK_EXCODE_WARN_REMOVE_ARGUMENT_REQUIRE },
	{ "TOO_HUGE_INDEX", INK_CORE_MOD_ID, INK_EXCODE_WARN_TOO_HUGE_INDEX },
	{ "EACH_ARGUMENT_REQUIRE", INK_CORE_MOD_ID, INK_EXCODE_WARN_EACH_ARGUMENT_REQUIRE },
	{ "FAILED_FINDING_METHOD", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_FINDING_METHOD },
	{ "WRONG_TYPE", INK_CORE_MOD_ID, INK_EXCODE_WARN_WRONG_TYPE },
	{ "WRONG_BASE_TYPE", INK_CORE_MOD_ID, INK_EXCODE_WARN_WRONG_BASE_TYPE },
	{ "TOO_LESS_ARGUMENT", INK_CORE_MOD_ID, INK_EXCODE_WARN_TOO_LESS_ARGUMENT },
	{ "WRONG_ARGUMENT_TYPE", INK_CORE_MOD_ID, INK_EXCODE_WARN_WRONG_ARGUMENT_TYPE },
	{ "NOT_PACKAGE", INK_CORE_MOD_ID, INK_EXCODE_WARN_NOT_PACKAGE },
	{ "SETTING_UNASSIGNABLE_GETTER", INK_CORE_MOD_ID, INK_EXCODE_WARN_SETTING_UNASSIGNABLE_GETTER },
	{ "SETTING_UNASSIGNABLE_SETTER", INK_CORE_MOD_ID, INK_EXCODE_WARN_SETTING_UNASSIGNABLE_SETTER },
	{ "TYPE_NAME_ARGUMENT_REQUIRE", INK_CORE_MOD_ID, INK_EXCODE_WARN_TYPE_NAME_ARGUMENT_REQUIRE },
	{ "WITH_ATTACHMENT_REQUIRE", INK_CORE_MOD_ID, INK_EXCODE_WARN_WITH_ATTACHMENT_REQUIRE },
	{ "FAILED_OPEN_FILE", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_OPEN_FILE },
	{ "FAILED_FIND_MOD", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_FIND_MOD },
	{ "FAILED_LOAD_MOD", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_LOAD_MOD },
	{ "FAILED_FIND_LOADER", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_FIND_LOADER },
	{ "FAILED_FIND_INIT", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_FIND_INIT },
	{ "FAILED_INIT_MOD", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_INIT_MOD },
	{ "HASH_TABLE_MAPPING_EXPECT_STRING", INK_CORE_MOD_ID, INK_EXCODE_WARN_HASH_TABLE_MAPPING_EXPECT_STRING },
	{ "DELETE_FUNCTION_ARGUMENT_REQUIRE", INK_CORE_MOD_ID, INK_EXCODE_WARN_DELETE_FUNCTION_ARGUMENT_REQUIRE },
	{ "EVAL_CALLED_WITHOUT_CURRENT_ENGINE", INK_CORE_MOD_ID, INK_EXCODE_WARN_EVAL_CALLED_WITHOUT_CURRENT_ENGINE },
	{ "INVALID_ARGUMENT_FOR_STRING_ADD", INK_CORE_MOD_ID, INK_EXCODE_WARN_INVALID_ARGUMENT_FOR_STRING_ADD },
	{ "INVALID_RETURN_VALUE_OF_TO_STRING", INK_CORE_MOD_ID, INK_EXCODE_WARN_INVALID_RETURN_VALUE_OF_TO_STRING },
	{ "LOAD_MOD_ON_WRONG_OS", INK_CORE_MOD_ID, INK_EXCODE_WARN_LOAD_MOD_ON_WRONG_OS },
	{ "NO_FILE_IN_MOD", INK_CORE_MOD_ID, INK_EXCODE_WARN_NO_FILE_IN_MOD },
	{ "ACTOR_CONFLICT", INK_CORE_MOD_ID, INK_EXCODE_WARN_ACTOR_CONFLICT },
	{ "FAILED_CREATE_PROCESS", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_CREATE_PROCESS },
	{ "CIRCULAR_PROTOTYPE_REFERENCE", INK_CORE_MOD_ID, INK_EXCODE_WARN_CIRCULAR_PROTOTYPE_REFERENCE },
	{ "STRING_INDEX_EXCEED", INK_CORE_MOD_ID, INK_EXCODE_WARN_STRING_INDEX_EXCEED },
	{ "SUB_STRING_EXCEED", INK_CORE_MOD_ID, INK_EXCODE_WARN_SUB_STRING_EXCEED },
	{ "UNREACHABLE_BONDING", INK_CORE_MOD_ID, INK_EXCODE_WARN_UNREACHABLE_BONDING },
	{ "DIVIDED_BY_ZERO", INK_CORE_MOD_ID, INK_EXCODE_WARN_DIVIDED_BY_ZERO },
	{ "UNDEFINED_CUSTOM_INTERRUPT_NAME", INK_CORE_MOD_ID, INK_EXCODE_WARN_UNDEFINED_CUSTOM_INTERRUPT_NAME },
	{ "UNREGISTERED_INTERRUPT_SIGNAL", INK_CORE_MOD_ID, INK_EXCODE_WARN_UNREGISTERED_INTERRUPT_SIGNAL },
	{ "TRAPPING_UNTRAPPED_SIGNAL", INK_CORE_MOD_ID, INK_EXCODE_WARN_TRAPPING_UNTRAPPED_SIGNAL },
	{ "PACKAGE_BROKEN", INK_CORE_MOD_ID, INK_EXCODE_WARN_PACKAGE_BROKEN },
	{ "FAILED_CREATE_COROUTINE", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_CREATE_COROUTINE },
	{ "COCALL_ARGUMENT_REQUIRE", INK_CORE_MOD_ID, INK_EXCODE_WARN_COCALL_ARGUMENT_REQUIRE },
	{ "WRONG_RET_VAL_FOR_COMPARE", INK_CORE_MOD_ID, INK_EXCODE_WARN_WRONG_RET_VAL_FOR_COMPARE },
	{ "ASSIGN_FIXED", INK_CORE_MOD_ID, INK_EXCODE_WARN_ASSIGN_FIXED },
	{ "FAILED_GET_CONSTANT", INK_CORE_MOD_ID, INK_EXCODE_WARN_FAILED_GET_CONSTANT },
	{ "FIX_REQUIRE_ASSIGNABLE_ARGUMENT", INK_CORE_MOD_ID, INK_EXCODE_WARN_FIX_REQUIRE_ASSIGNABLE_ARGUMENT },
	{ "SLICE_REQUIRE_NUMERIC", INK_CORE_MOD_ID, INK_EXCODE_WRAN_SLICE_REQUIRE_NUMERIC },
	{ "SLICE_REQUIRE_NON_ZERO_RANGE", INK_CORE_MOD_ID, INK_EXCODE_WRAN_SLICE_REQUIRE_NON_ZERO_RANGE }
};

void Ink_GlobalMethodInit(Ink_InterpreteEngine *engine, Ink_ContextChain *context)
{
	Ink_ContextObject *global = context->getGlobal();

	global->setSlot_c("p", new Ink_FunctionObject(engine, Ink_Print));
	global->setSlot_c("eval", new Ink_FunctionObject(engine, Ink_Eval));
	global->setSlot_c("import", new Ink_FunctionObject(engine, Ink_Import));
	global->setSlot_c("typename", new Ink_FunctionObject(engine, Ink_TypeName));
	global->setSlot_c("intval", new Ink_FunctionObject(engine, Ink_IntVal));
	global->setSlot_c("numval", new Ink_FunctionObject(engine, Ink_NumVal));
	global->setSlot_c("unicode", new Ink_FunctionObject(engine, Ink_UnicodeToString));
	global->setSlot_c("cocall", new Ink_FunctionObject(engine, Ink_CoroutineCall));

	global->setSlot_c("abort", new Ink_FunctionObject(engine, Ink_Abort));
	global->setSlot_c("regsig", new Ink_FunctionObject(engine, Ink_RegisterSignal));
	global->setSlot_c("delsig", new Ink_FunctionObject(engine, Ink_DeleteSignal));

	global->setSlot_c("debug", new Ink_FunctionObject(engine, Ink_Debug));
	global->setSlot_c("where", new Ink_FunctionObject(engine, Ink_Where));

	Ink_Object *engine_obj = new Ink_Object(engine);

	Ink_HashTable *errmode_hash = engine_obj->setSlot_c("errmode", new Ink_String(engine, ""));
	errmode_hash->setGetter(new Ink_FunctionObject(engine, Ink_GetErrorMode));
	errmode_hash->setSetter(new Ink_FunctionObject(engine, Ink_SetErrorMode));
	global->setSlot_c("engine", engine_obj);

	Ink_Object *array_cons = new Ink_FunctionObject(engine, Ink_ArrayConstructor);
	global->setSlot_c("Array", array_cons);

	global->setSlot_c("undefined", UNDEFINED);
	global->setSlot_c("?", UNDEFINED);
	global->setSlot_c("null", NULL_OBJ);
	global->setSlot_c("_", new Ink_Unknown(engine));
	global->setSlot_c("false", FALSE_OBJ);

	global->setSlot_c("errno", new Ink_ErrnoObject(engine, sizeof(ink_errno_map) /
														   sizeof(Ink_ErrnoMap),
												   ink_errno_map));

	Ink_applyAllModules(engine, context);

	return;
}

}
