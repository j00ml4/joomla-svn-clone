SUBDIRS = com_alpha

all: md5sums
	@for i in $(SUBDIRS); do \
	echo "Building $$i..."; \
	(cd $$i; rm -rf ../$$i.zip; zip -r ../$$i.zip *); done

md5sums:
	@for i in $(SUBDIRS); do \
	echo "Building MD5SUM for $$i..."; \
	cd $$i/site; \
	echo "Processing site"; \
	rm MD5SUM; \
	for k in `find -type f`; do echo $$k; md5sum $$k | sed -e "s/\([a-f0-9]*\)\(..\)\(.*\)/\3\2\1/" >> MD5SUM; done; \
	cd ../admin; \
	echo "Processing admin"; \
	rm MD5SUM; \
	for k in `find -type f`; do echo $$k; md5sum $$k >> MD5SUM; done; \
	done

clean:
	rm -rf *.zip
