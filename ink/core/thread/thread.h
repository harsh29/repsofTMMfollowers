#ifndef _THREAD_H_
#define _THREAD_H_

#include <map>
#include <vector>
#include <stdio.h>
#include <malloc.h>
#include <pthread.h>
#include "../inttype.h"

#define getThreadID_raw() (pthread_self())

namespace ink {

typedef Ink_UInt64 ThreadIDRaw;
typedef Ink_UInt64 ThreadID;

class MutexLock {
public:
	pthread_mutex_t _lock;

	void init()
	{
		pthread_mutex_init(&_lock, NULL);
	}

	void lock()
	{
		pthread_mutex_lock(&_lock);
	}

	void unlock()
	{
		pthread_mutex_unlock(&_lock);
	}
};

class Ink_Expression;
class Ink_ContextChain;

class EvalArgument {
public:
	Ink_Expression *exp;
	Ink_ContextChain *context;

	EvalArgument(Ink_Expression *exp, Ink_ContextChain *context)
	: exp(exp), context(context)
	{ }
};

typedef std::map<ThreadIDRaw, ThreadID> ThreadIDMap;
typedef std::vector<ThreadIDMap> ThreadIDMapStack;
typedef std::vector<pthread_t *> ThreadPool;
typedef ThreadIDMapStack::size_type ThreadLayerType;

}

#endif
