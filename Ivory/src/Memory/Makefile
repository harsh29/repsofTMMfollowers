TARGET = mem.o
CFLAGS = -c -g -DDEBUG -Wall
OBJS = memory.o storage.o

$(TARGET):$(OBJS)
	ld -r -o $@ $(OBJS)
testp : $(OBJS) main.o
	$(CC) -o $@ $(OBJS) main.o
.c.o:
	$(CC) $(CFLAGS) -I../Includes $*.c
main.o: main.c ../Includes/MEM.h
memory.o: memory.c memory.h ../Includes/MEM.h
storage.o: storage.c memory.h ../Includes/MEM.h
