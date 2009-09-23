# Standard parent build
SHELL=/bin/sh
MAKE = make

SUBDIRS = administrator site 

all:
	@for i in $(SUBDIRS); do \
	echo "Making $$i"; \
	(cd $$i; $(MAKE) all); done

clean:
	@for i in $(SUBDIRS); do \
	echo "Cleaning $$i"; \
	(cd $$i; $(MAKE) clean); done

.phony: all clean
