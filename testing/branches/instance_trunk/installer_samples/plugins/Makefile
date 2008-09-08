SUBDIRS = plgSystemAlpha 

all:
	@for i in $(SUBDIRS); do \
	echo "Building $$i..."; \
	(cd $$i; rm -rf ../$$i.zip; zip -r ../$$i.zip *); done

clean:
	rm -rf *.zip
