%{
	#include <sstream>
	#include <stdio.h>
	#include <stdlib.h>
	#include <string.h>
	#include "syntax.h"
	#include "core/expression.h"
	#include "core/object.h"
	#include "core/error.h"
	#include "core/general.h"
	#include "core/interface/engine.h"
	#include "core/interface/setting.h"
	#define SET_LINE_NO(exp) (exp->line_number = InkParser_getCurrentLineno())
	#define YYERROR_VERBOSE 1
	#define YYDEBUG 1

	using namespace ink;

	extern int yylex();
	void yyerror(const char *msg) {
		const char *tmp = InkParser_getParseEngine()->getFilePath();
		fprintf(stderr, "%s: %sline %ld: %s\n", tmp ? tmp : "<unknown input>",
				InkParser_getErrPrefix(), InkParser_getCurrentLineno(), msg);
	}

%}

%union {
	ink::Ink_Expression					*expression;
	ink::Ink_ParamList					*parameter;
	ink::Ink_ExpressionList				*expression_list;
	ink::Ink_Argument					*argument;
	ink::Ink_ArgumentList				*argument_list;
	ink::Ink_HashTableMapping			*hash_table_mapping;
	ink::Ink_HashTableMappingSingle		*hash_table_mapping_single;
	std::string							*string;
	ink::Ink_InterruptSignal			signal;
	ink::Ink_FunctionAttribution		*function_attr;
	int									split;
	int									token;
}

/* constants & identifiers */
%token <string>
	TIDENTIFIER
	TINTEGER
	TFLOAT
	TSTRING
	TPROTOCOL

/* keywords */
%token <token>
	TNEW				// new
	TDELETE				// delete
	TCLONE				// clone
	TFIX				// fix

	TFUNC				// fn
	TINLINE				// inline
	TMACRO				// macro
	
	TDO					// do
	TEND				// end
	
	TIMPORT				// import

	TRETURN				// retn
	TBREAK				// break
	TCONTINUE			// continue
	TDROP				// drop
	TTHROW				// throw
	TRETRY				// retry
	TEXIT				// exit

	TYIELD				// yield
	
	TWITH				// with
	TEXPAND				// expand

/* tokens */
%token <token>
	TLPAREN				// (
	TRPAREN				// )
	TLBRAKT				// [
	TRBRAKT				// ]
	TLBRACE				// {
	TRBRACE				// }

	TECLI				// ...
	TCOMMA				// ,

/* splits */
%token <token>
	TNL					// [\n\r]
	TSEMICOLON			// ;

/* assign operators */
%token <token>
	TASSIGN				// =
	TAOUT				// >>=
	TAINS				// <<=
	TAADD				// +=
	TASUB				// -=
	TAMUL				// *=
	TADIV				// /=
	TAMOD				// %=
	TAAND				// &=
	TAXOR				// ^=
	TAOR				// |=
	TALAND				// &&=
	TALOR				// ||=

/* binary operators */
%token <token>
	TADD				// +
	TSUB				// -
	TMUL				// *
	TDIV				// /
	TMOD				// %
	TBAND				// &
	TBXOR				// ^
	TBOR				// |
	TINS				// <<
	TOUT				// >>
	TCOLON				// :
	TDCOLON				// ::
	TARR				// ->

/* unary operators */
%token <token>
	TDADD				// ++
	TDSUB				// --
	TDOT				// .
	TDNOT				// !!
	TLNOT				// !
	TBINV				// ~

/* condition operators */
%token <token>
	TCSS				// <=>
	TCLE				// <=
	TCLT				// <
	TCGE				// >=
	TCGT				// >
	TCEQ				// ==
	TCNE				// !=
	TLAND				// &&
	TLOR				// ||

/* expressions */
/* some names are quoted from ANSI C grammar */
%type <expression>
	expression																/* priority */
		primary_expression single_element_expression						/* highest	*/
		function_expression space_hash_expression dot_hash_expression		/* ^		*/
		postfix_expression													/* |		*/
		unary_expression													/* |		*/
		multiplicative_expression											/* |		*/
		additive_expression													/* |		*/
		shift_expression													/* |		*/
		relational_expression												/* |		*/
		equality_expression													/* |		*/
		and_expression														/* |		*/
		exclusive_or_expression												/* |		*/
		inclusive_or_expression												/* |		*/
		yield_expression													/* |		*/
		logical_and_expression												/* |		*/
		logical_or_expression												/* |		*/
		assignment_expression												/* |		*/
		interrupt_expression												/* |		*/
		nestable_expression import_expression comma_expression				/* lowest	*/

	/* trivial */
	/* not exactly an expression, but returns expression */
	direct_argument_attachment_expression
	block

/* expression list */
%type <expression_list>
	top_level_expression_list
	top_level_expression_list_opt
	expression_list
	expression_list_opt
	empty_element_list
	element_list_sub
	element_list
	element_list_opt
	function_body

/* parameter */
%type <parameter>
	param_list
	param_opt
	param_list_sub
	param_decl_opt

/* argument */
%type <argument>
	argument_single
	direct_argument_attachment_expression_prefix

%type <argument_list>
	empty_argument_list
	argument_list_sub
	argument_list
	argument_list_opt
	argument_attachment
	argument_attachment_opt
	argument_list_without_paren

/* hash table */
%type <hash_table_mapping>
	hash_table_mapping
	hash_table_mapping_opt

%type <hash_table_mapping_single> hash_table_mapping_single

/* line number offset */
%type <split> split split_single split_opt

/* interrupt signal */
%type <signal>
	interrupt_signal
	interrupt_signal_ext
	interrupt_signal_list
	function_attr_sub

/* function attribution */
%type <function_attr> function_attr

%start parse_unit

%%

parse_unit
	: top_level_expression_list_opt
	{
		InkParser_getParseEngine()->top_level = *$1;
		delete $1;
	}
	;

nll
	: TNL
	| nll TNL
	;

nllo
	: /* empty */
	| nll
	;

split
	: nll
	{
		$$ = -1;
	}
	| TSEMICOLON nll
	{
		$$ = 0;
	}
	| TSEMICOLON
	{
		$$ = 0;
	}
	;

split_single
	: TNL
	{
		$$ = -1;
	}
	| TSEMICOLON
	{
		$$ = 0;
	}
	;

split_opt
	: /* empty */
	{
		$$ = 0;
	}
	| split
	;

top_level_expression_list
	: expression
	{
		$$ = new Ink_ExpressionList();
		$$->push_back($1);
		if (InkParser_getParseEngine()->getFilePath()) {
			$1->file_name
			= ($1->file_name_p
			   = new string(InkParser_getParseEngine()->getFilePath()))->c_str();
		}
	}
	| top_level_expression_list split expression
	{
		(*$1)[$1->size() - 1]->line_number += $2;
		$1->push_back($3);
		$$ = $1;
		$3->file_name = $$->front()->file_name;
	}
	;

top_level_expression_list_opt
	: split_opt
	{
		$$ = new Ink_ExpressionList();
	}
	| split_opt top_level_expression_list split_opt
	{
		$$ = $2;
	}
	;

expression_list
	: expression
	{
		$$ = new Ink_ExpressionList();
		$$->push_back($1);
		if (InkParser_getParseEngine()->getFilePath()) {
			$1->file_name
			= ($1->file_name_p
			   = new string(InkParser_getParseEngine()->getFilePath()))->c_str();
		}
	}
	| expression_list split expression
	{
		(*$1)[$1->size() - 1]->line_number += $2;
		$1->push_back($3);
		$$ = $1;
		$3->file_name = $$->front()->file_name;
	}
	;

expression_list_opt
	: split_opt
	{
		$$ = new Ink_ExpressionList();
	}
	| split_opt expression_list split_opt
	{
		$$ = $2;
	}
	;

expression
	: nestable_expression
	| comma_expression
	| import_expression
	;

interrupt_signal
	: TRETURN
	{
		$$ = INTER_RETURN;
	}
	| TBREAK
	{
		$$ = INTER_BREAK;
	}
	| TCONTINUE
	{
		$$ = INTER_CONTINUE;
	}
	| TDROP
	{
		$$ = INTER_DROP;
	}
	| TTHROW
	{
		$$ = INTER_THROW;
	}
	| TRETRY
	{
		$$ = INTER_RETRY;
	}
	| TEXIT
	{
		$$ = INTER_EXIT;
	}
	;

interrupt_signal_ext
	: interrupt_signal
	| TIDENTIFIER
	{
		if (*$1 == "default")
			$$ = DEFAULT_SIGNAL;
		else if (*$1 == "all")
			$$ = ALL_SIGNAL;
		else {
			stringstream strm;
			strm << "Unknown interrupt signal '" << $1->c_str() << "'";
			yyerror(strm.str().c_str());
			delete $1;
			return 0;
		}

		delete $1;
	}
	;

interrupt_signal_list
	: interrupt_signal_ext
	| interrupt_signal_ext TCOMMA nllo interrupt_signal_list
	{
		$$ = $$ | $4;
	}
	;

interrupt_expression
	: interrupt_signal
	{
		$$ = new Ink_InterruptExpression($1, NULL);
		SET_LINE_NO($$);
	}
	| interrupt_signal nestable_expression
	{
		$$ = new Ink_InterruptExpression($1, $2);
		SET_LINE_NO($$);
	}
	| TCOLON TIDENTIFIER
	{
		$$ = new Ink_InterruptExpression($2, NULL);
		SET_LINE_NO($$);
	}
	| TCOLON TIDENTIFIER nestable_expression
	{
		$$ = new Ink_InterruptExpression($2, $3);
		SET_LINE_NO($$);
	}
	;

import_expression
	: TIMPORT nllo argument_list_without_paren
	{
		$$ = new Ink_CallExpression(new Ink_IdentifierExpression(new string("import")), *$3);
		delete $3;
		SET_LINE_NO($$);
	}
	;

comma_expression
	: nestable_expression TCOMMA nllo nestable_expression
	{
		Ink_CommaExpression *tmp = new Ink_CommaExpression();
		tmp->exp_list.push_back($1);
		tmp->exp_list.push_back($4);
		$$ = tmp;
		SET_LINE_NO($$);
	}
	| comma_expression TCOMMA nllo nestable_expression
	{
		as<Ink_CommaExpression>($1)->exp_list.push_back($4);
		$$ = $1;
		SET_LINE_NO($$);
	}
	;

nestable_expression
	: assignment_expression
	| interrupt_expression
	;

assignment_expression
	: logical_or_expression
	| logical_or_expression TASSIGN nllo assignment_expression
	{
		$$ = new Ink_AssignmentExpression($1, $4);
		SET_LINE_NO($$);
	}
	| logical_or_expression TARR nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("->")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| logical_or_expression TAOUT nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string(">>"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TAINS nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("<<"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TAADD nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("+"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TASUB nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("-"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TAMUL nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("*"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TADIV nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("/"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TAMOD nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("%"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TAAND nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("&"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TAXOR nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("^"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TAOR nllo assignment_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		Ink_HashExpression *tmp_hash = new Ink_HashExpression($1, new string("|"), false);
		Ink_CallExpression *tmp_call = new Ink_CallExpression(tmp_hash, arg);

		$$ = new Ink_AssignmentExpression($1, tmp_call);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_call);
		SET_LINE_NO(tmp_hash);
	}
	| logical_or_expression TALAND nllo assignment_expression
	{
		Ink_LogicExpression *tmp_logic = new Ink_LogicExpression($1, $4, INK_LOGIC_AND, false);

		$$ = new Ink_AssignmentExpression($1, tmp_logic);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_logic);
	}
	| logical_or_expression TALOR nllo assignment_expression
	{
		Ink_LogicExpression *tmp_logic = new Ink_LogicExpression($1, $4, INK_LOGIC_OR, false);

		$$ = new Ink_AssignmentExpression($1, tmp_logic);
		SET_LINE_NO($$);
		SET_LINE_NO(tmp_logic);
	}
	;

logical_or_expression
	: logical_and_expression
	| logical_or_expression TLOR nllo logical_and_expression
	{
		$$ = new Ink_LogicExpression($1, $4, INK_LOGIC_OR);
		SET_LINE_NO($$);
	}
	;

logical_and_expression
	: yield_expression
	| logical_and_expression TLAND nllo yield_expression
	{
		$$ = new Ink_LogicExpression($1, $4, INK_LOGIC_AND);
		SET_LINE_NO($$);
	}
	;

yield_expression
	: inclusive_or_expression
	| TYIELD
	{
		$$ = new Ink_YieldExpression(NULL);
		SET_LINE_NO($$);
	}
	| TYIELD yield_expression
	{
		$$ = new Ink_YieldExpression($2);
		SET_LINE_NO($$);
	}
	;

inclusive_or_expression
	: exclusive_or_expression
	| inclusive_or_expression TBOR nllo exclusive_or_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("|")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

exclusive_or_expression
	: and_expression
	| exclusive_or_expression TBXOR nllo and_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("^")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

and_expression
	: equality_expression
	| and_expression TBAND nllo equality_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("&")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

equality_expression
	: relational_expression
	| equality_expression TCEQ nllo relational_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("==")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| equality_expression TCNE nllo relational_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("!=")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

relational_expression
	: shift_expression
	| relational_expression TCSS nllo shift_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("<=>")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| relational_expression TCLT nllo shift_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("<")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| relational_expression TCGT nllo shift_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string(">")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| relational_expression TCLE nllo shift_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("<=")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| relational_expression TCGE nllo shift_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string(">=")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

shift_expression
	: additive_expression
	| shift_expression TINS nllo additive_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("<<")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| shift_expression TOUT nllo additive_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));

		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string(">>")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

additive_expression
	: multiplicative_expression
	| additive_expression TADD nllo multiplicative_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("+")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| additive_expression TSUB nllo multiplicative_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("-")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

multiplicative_expression
	: unary_expression
	| multiplicative_expression TMUL nllo unary_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("*")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| multiplicative_expression TDIV nllo unary_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("/")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| multiplicative_expression TMOD nllo unary_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("%")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

argument_single
	: nestable_expression
	{
		$$ = new Ink_Argument($1);
	}
	| TEXPAND nllo nestable_expression
	{
		$$ = new Ink_Argument(true, $3);
	}
	;

empty_argument_list
	: TCOMMA
	{
		$$ = new Ink_ArgumentList();
	}
	| empty_argument_list nllo TCOMMA
	{
		$1->push_back(NULL);
		$$ = $1;
	}
	;

argument_list_sub
	: argument_single
	{
		$$ = new Ink_ArgumentList();
		$$->push_back($1);
	}
	| empty_argument_list nllo argument_single
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(NULL);
		$$->insert($$->end(), $1->begin(), $1->end());
		$$->push_back($3);
		delete $1;
	}
	| argument_list_sub nllo empty_argument_list nllo argument_single
	{
		$1->insert($1->end(), $3->begin(), $3->end());
		$1->push_back($5);
		$$ = $1;
		delete $3;
	}
	;

argument_list
	: nllo empty_argument_list nllo
	{
		$2->push_back(NULL);
		$2->push_back(NULL);
		$$ = $2;
	}
	| nllo argument_list_sub nllo
	{
		$$ = $2;
	}
	| nllo argument_list_sub nllo empty_argument_list nllo
	{
		$2->insert($2->end(), $4->begin(), $4->end());
		$2->push_back(NULL);
		$$ = $2;
		delete $4;
	}
	;

argument_list_without_paren
	: argument_single
	{
		$$ = new Ink_ArgumentList();
		$$->push_back($1);
	}
	| argument_list_without_paren TCOMMA nllo argument_single
	{
		$1->push_back($4);
		$$ = $1;
	}
	;

argument_list_opt
	: /* empty */ 
	{
		$$ = new Ink_ArgumentList();
	}
	| argument_list
	;

block
	: TLBRACE expression_list_opt TRBRACE
	{
		$$ = new Ink_FunctionExpression(Ink_ParamList(), *$2, true);
		delete $2;
		SET_LINE_NO($$);
	}
	| TDO expression_list_opt TEND
	{
		$$ = new Ink_FunctionExpression(Ink_ParamList(), *$2, true);
		delete $2;
		SET_LINE_NO($$);
	}
	| TLBRACE nllo TBOR param_opt TBOR expression_list_opt TRBRACE
	{
		$$ = new Ink_FunctionExpression(*$4, *$6, true);
		delete $4;
		delete $6;
		SET_LINE_NO($$);
	}
	| TDO nllo TBOR param_opt TBOR expression_list_opt TEND
	{
		$$ = new Ink_FunctionExpression(*$4, *$6, true);
		delete $4;
		delete $6;
		SET_LINE_NO($$);
	}
	| TCOLON nllo expression split_single
	{
		Ink_ExpressionList exp_list = Ink_ExpressionList();
		exp_list.push_back($3);
		$$ = new Ink_FunctionExpression(Ink_ParamList(), exp_list, true);
		SET_LINE_NO($$);
	}
	| TCOLON nllo TBOR param_opt TBOR nllo expression split_single
	{
		Ink_ExpressionList exp_list = Ink_ExpressionList();
		exp_list.push_back($7);
		$$ = new Ink_FunctionExpression(*$4, exp_list, true);
		delete $4;
		SET_LINE_NO($$);
	}
	;

unary_expression
	: postfix_expression
	| TNEW nllo postfix_expression TLPAREN argument_list_opt TRPAREN
	{
		$$ = new Ink_CallExpression($3, *$5, true);
		delete $5;
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TNEW nllo postfix_expression TLPAREN argument_list_opt TRPAREN argument_attachment
	{
		$5->insert($5->end(), $7->begin(), $7->end());
		$$ = new Ink_CallExpression($3, *$5, true);
		delete $5;
		delete $7;
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TCLONE nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("clone")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TDELETE nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("delete")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TFIX nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("fix")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TADD nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("+u")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TSUB nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("-u")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TDNOT nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("!!")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TLNOT nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("!")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TBINV nllo unary_expression
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($3, new string("~")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| TDADD nllo unary_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument(new Ink_NumericExpression(1)));
		$$ = new Ink_AssignmentExpression($3, new Ink_CallExpression(
												  new Ink_HashExpression($3, new string("+")),
												  arg), false, false);
		SET_LINE_NO($$);
	}
	| TDSUB nllo unary_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument(new Ink_NumericExpression(1)));
		$$ = new Ink_AssignmentExpression($3, new Ink_CallExpression(
												  new Ink_HashExpression($3, new string("-")),
												  arg), false, false);
		SET_LINE_NO($$);
	}
	;

argument_attachment
	: block
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(new Ink_Argument($1));
	}
	| TIDENTIFIER nllo TLPAREN element_list_opt TRPAREN
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(new Ink_Argument(new Ink_StringExpression($1)));
		$$->push_back(new Ink_Argument(new Ink_ListExpression(*$4)));
		delete $4;
	}
	| TWITH nllo function_expression
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(new Ink_Argument(true, $3));
	}
	| block argument_attachment
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(new Ink_Argument($1));
		$$->insert($$->end(), $2->begin(), $2->end());
		delete $2;
	}
	| TIDENTIFIER nllo argument_attachment
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(new Ink_Argument(new Ink_StringExpression($1)));
		$$->insert($$->end(), $3->begin(), $3->end());
		delete $3;
	}
	| TIDENTIFIER nllo TLPAREN element_list_opt TRPAREN argument_attachment
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(new Ink_Argument(new Ink_StringExpression($1)));
		$$->push_back(new Ink_Argument(new Ink_ListExpression(*$4)));
		$$->insert($$->end(), $6->begin(), $6->end());
		delete $4;
		delete $6;
	}
	| TWITH nllo function_expression argument_attachment
	{
		$$ = new Ink_ArgumentList();
		$$->push_back(new Ink_Argument(true, $3));
		$$->insert($$->end(), $4->begin(), $4->end());
		delete $4;
	}
	;

argument_attachment_opt
	: /* empty */
	{
		$$ = new Ink_ArgumentList();
	}
	| argument_attachment
	;

space_hash_expression
	: primary_expression TIDENTIFIER
	{
		$$ = new Ink_HashExpression($1, $2);
		SET_LINE_NO($$);
	}
	| space_hash_expression TIDENTIFIER
	{
		$$ = new Ink_HashExpression($1, $2);
		SET_LINE_NO($$);
	}
	;

dot_hash_expression
	: postfix_expression TDOT nllo TIDENTIFIER
	{
		$$ = new Ink_HashExpression($1, $4);
		SET_LINE_NO($$);
	}
	;

direct_argument_attachment_expression_prefix
	: block
	{
		$$ = new Ink_Argument($1);
	}
	| TWITH nllo function_expression
	{
		$$ = new Ink_Argument(true, $3);
	}
	;

direct_argument_attachment_expression
	: dot_hash_expression
	| space_hash_expression
	| function_expression
	;

postfix_expression
	: function_expression
	| space_hash_expression
	| dot_hash_expression
	| postfix_expression TLPAREN argument_list_opt TRPAREN argument_attachment_opt
	{
		$3->insert($3->end(), $5->begin(), $5->end());
		$$ = new Ink_CallExpression($1, *$3);
		delete $3;
		delete $5;
		SET_LINE_NO($$);
	}
	| direct_argument_attachment_expression direct_argument_attachment_expression_prefix argument_attachment_opt
	{
		Ink_ArgumentList arg_list = Ink_ArgumentList();
		arg_list.push_back($2);
		arg_list.insert(arg_list.end(), $3->begin(), $3->end());
		$$ = new Ink_CallExpression($1, arg_list);
		delete $3;
		SET_LINE_NO($$);
	}
	| postfix_expression TLBRAKT argument_list_opt TRBRAKT
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("[]")), *$3);
		delete $3;
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| postfix_expression TDADD
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument(new Ink_NumericExpression(1)));
		$$ = new Ink_AssignmentExpression($1, new Ink_CallExpression(
												  new Ink_HashExpression($1, new string("+")),
												  arg), true, false);
		SET_LINE_NO($$);
	}
	| postfix_expression TDSUB
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument(new Ink_NumericExpression(1)));
		$$ = new Ink_AssignmentExpression($1, new Ink_CallExpression(
												  new Ink_HashExpression($1, new string("-")),
												  arg), true, false);
		SET_LINE_NO($$);
	}
	| postfix_expression TLNOT
	{
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("!p")),
									Ink_ArgumentList());
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	| postfix_expression TDCOLON nllo function_expression
	{
		Ink_ArgumentList arg = Ink_ArgumentList();
		arg.push_back(new Ink_Argument($4));
		$$ = new Ink_CallExpression(new Ink_HashExpression($1, new string("::")), arg);
		SET_LINE_NO($$);
		SET_LINE_NO(as<Ink_CallExpression>($$)->callee);
	}
	;

param_multi_decl
	: TMUL nllo TBAND nllo
	| TBAND nllo TMUL nllo

param_list_sub
	: TIDENTIFIER
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter($1, false, false));
	}
	| TMUL nllo TIDENTIFIER
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter($3, false, false, true));
	}
	| TBAND nllo TIDENTIFIER
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter($3, true, false));
	}
	| param_multi_decl TIDENTIFIER
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter($2, true, false, true));
	}
	| param_list TCOMMA nllo TIDENTIFIER
	{
		$1->push_back(Ink_Parameter($4, false, false));
		$$ = $1;
	}
	| param_list TCOMMA nllo TMUL nllo TIDENTIFIER
	{
		$1->push_back(Ink_Parameter($6, false, false, true));
		$$ = $1;
	}
	| param_list TCOMMA nllo TBAND nllo TIDENTIFIER
	{
		$1->push_back(Ink_Parameter($6, true, false));
		$$ = $1;
	}
	| param_list TCOMMA nllo param_multi_decl TIDENTIFIER
	{
		$1->push_back(Ink_Parameter($5, true, false, true));
		$$ = $1;
	}
	| param_list TCOMMA nllo TIDENTIFIER TECLI
	{
		$1->push_back(Ink_Parameter($4, false, true));
		$$ = $1;
	}
	| param_list TCOMMA nllo TBAND nllo TIDENTIFIER TECLI
	{
		$1->push_back(Ink_Parameter($6, true, true));
		$$ = $1;
	}
	| param_list TCOMMA nllo TECLI
	{
		$1->push_back(Ink_Parameter(new string("args"), false, true));
		$$ = $1;
	}
	| param_list TCOMMA nllo TBAND nllo TECLI
	{
		$1->push_back(Ink_Parameter(new string("args"), true, true));
		$$ = $1;
	}
	;

param_list
	: param_list_sub
	| TIDENTIFIER TECLI
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter($1, false, true));
	}
	| TBAND nllo TIDENTIFIER TECLI
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter($3, true, true));
	}
	| TECLI
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter(new string("args"), false, true));
	}
	| TBAND nllo TECLI
	{
		$$ = new Ink_ParamList();
		$$->push_back(Ink_Parameter(new string("args"), true, true));
	}
	;

param_opt
	: nllo
	{
		$$ = new Ink_ParamList();
	}
	| nllo param_list nllo
	{
		$$ = $2;
	}
	;

function_attr_sub
	: TLBRAKT nllo interrupt_signal_list nllo TRBRAKT
	{
		$$ = $3;
	}
	| TBXOR nllo TLBRAKT nllo interrupt_signal_list nllo TRBRAKT
	{
		$$ = DEFAULT_SIGNAL ^ (DEFAULT_SIGNAL & $5);
	}
	| TBAND nllo TLBRAKT nllo interrupt_signal_list nllo TRBRAKT
	{
		$$ = DEFAULT_SIGNAL | $5;
	}
	| function_attr_sub nllo TLBRAKT nllo interrupt_signal_list nllo TRBRAKT
	{
		$$ = $5;
	}
	| function_attr_sub nllo TBXOR nllo TLBRAKT nllo interrupt_signal_list nllo TRBRAKT
	{
		$$ = $1 ^ ($1 & $7);
	}
	| function_attr_sub nllo TBAND nllo TLBRAKT nllo interrupt_signal_list nllo TRBRAKT
	{
		$$ = $1 | $7;
	}
	;

function_attr
	: function_attr_sub
	{
		$$ = new Ink_FunctionAttribution($1);
	}
	;

function_body
	: TLBRACE expression_list_opt TRBRACE
	{
		$$ = $2;
	}
	| TDO expression_list_opt TEND
	{
		$$ = $2;
	}
	| TCOLON nllo expression split_single
	{
		$$ = new Ink_ExpressionList();
		$$->push_back($3);
	}
	;

param_decl_opt
	: /* empty */
	{
		$$ = new Ink_ParamList();
	}
	| TLPAREN param_opt TRPAREN nllo
	{
		$$ = $2;
	}
	;

function_expression
	: primary_expression
	| TFUNC nllo param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$3, *$4);
		delete $3;
		delete $4;
		SET_LINE_NO($$);
	}
	| TFUNC nllo function_attr param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$4, *$5, $3);
		delete $4;
		delete $5;
		SET_LINE_NO($$);
	}
	| TINLINE nllo param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$3, *$4, true);
		delete $3;
		delete $4;
		SET_LINE_NO($$);
	}
	| TINLINE nllo function_attr param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$4, *$5, $3, true);
		delete $4;
		delete $5;
		SET_LINE_NO($$);
	}
	| TMACRO nllo param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$3, *$4, false, true);
		delete $3;
		delete $4;
		SET_LINE_NO($$);
	}
	| TMACRO nllo function_attr param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$4, *$5, $3, false, true);
		delete $4;
		delete $5;
		SET_LINE_NO($$);
	}
	| TPROTOCOL nllo param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$3, *$4, $1);
		delete $3;
		delete $4;
		SET_LINE_NO($$);
	}
	| TPROTOCOL nllo function_attr param_decl_opt function_body
	{
		$$ = new Ink_FunctionExpression(*$4, *$5, $3, $1);
		delete $4;
		delete $5;
		SET_LINE_NO($$);
	}
	;

empty_element_list
	: TCOMMA
	{
		$$ = new Ink_ExpressionList();
	}
	| empty_element_list nllo TCOMMA
	{
		$1->push_back(NULL);
		$$ = $1;
	}
	;

element_list_sub
	: nestable_expression
	{
		$$ = new Ink_ExpressionList();
		$$->push_back($1);
	}
	| empty_element_list nllo nestable_expression
	{
		$$ = new Ink_ExpressionList();
		$$->push_back(NULL);
		$$->insert($$->end(), $1->begin(), $1->end());
		$$->push_back($3);
		delete $1;
	}
	| element_list_sub nllo empty_element_list nllo nestable_expression
	{
		$1->insert($1->end(), $3->begin(), $3->end());
		$1->push_back($5);
		$$ = $1;
		delete $3;
	}
	;

element_list
	: nllo empty_element_list nllo
	{
		$2->push_back(NULL);
		$2->push_back(NULL);
		$$ = $2;
	}
	| nllo element_list_sub nllo
	{
		$$ = $2;
	}
	| nllo element_list_sub nllo empty_element_list nllo
	{
		$2->insert($2->end(), $4->begin(), $4->end());
		$2->push_back(NULL);
		$$ = $2;
		delete $4;
	}
	;

element_list_opt
	: /* empty */ 
	{
		$$ = new Ink_ExpressionList();
	}
	| element_list
	;

hash_table_mapping_single
	: TIDENTIFIER nllo TCOLON nllo nestable_expression
	{
		$$ = new Ink_HashTableMappingSingle($1, $5);
		SET_LINE_NO($$);
	}
	| TSTRING nllo TCOLON nllo nestable_expression
	{
		$$ = new Ink_HashTableMappingSingle($1, $5);
		SET_LINE_NO($$);
	}
	| TLBRAKT nestable_expression TRBRAKT nllo TCOLON nllo nestable_expression
	{
		$$ = new Ink_HashTableMappingSingle($2, $7);
		SET_LINE_NO($$);
	}
	;

hash_table_mapping
	: hash_table_mapping_single
	{
		$$ = new Ink_HashTableMapping();
		$$->push_back($1);
	}
	| hash_table_mapping nllo TCOMMA nllo hash_table_mapping_single
	{
		$1->push_back($5);
		$$ = $1;
	}
	;

hash_table_mapping_opt
	: nllo
	{
		$$ = new Ink_HashTableMapping();
	}
	| nllo hash_table_mapping nllo
	{
		$$ = $2;
	}
	;

single_element_expression
	: TINTEGER
	{
		$$ = Ink_NumericExpression::parseIntExp(*$1);
		delete $1;

		if (!$$) {
			yyerror("Failed to parse integer");
			return -1;
		}

		SET_LINE_NO($$);
	}
	| TFLOAT
	{
		$$ = Ink_NumericExpression::parseFloatExp(*$1);
		delete $1;

		if (!$$) {
			yyerror("Failed to parse float");
			return -1;
		}

		SET_LINE_NO($$);
	}
	| TSTRING
	{
		$$ = new Ink_StringExpression($1);
		SET_LINE_NO($$);
	}
	| TIDENTIFIER
	{
		$$ = new Ink_IdentifierExpression($1);
		SET_LINE_NO($$);
	}
	;

primary_expression
	: single_element_expression
	| TLPAREN nllo nestable_expression nllo TRPAREN
	{
		$$ = $3;
		SET_LINE_NO($$);
	}
	| TLPAREN nllo comma_expression nllo TRPAREN
	{
		$$ = $3;
		SET_LINE_NO($$);
	}
	| TLBRAKT element_list_opt TRBRAKT
	{
		$$ = new Ink_ListExpression(*$2);
		delete $2;
		SET_LINE_NO($$);
	}
	| TLBRACE hash_table_mapping_opt TRBRACE
	{
		$$ = new Ink_HashTableExpression(*$2);
		delete $2;
		SET_LINE_NO($$);
	}
	;

%%
