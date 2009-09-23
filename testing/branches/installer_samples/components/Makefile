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
	rm MD5SUMS; \
	for k in `find -type f | grep -v .svn`; do echo $$k; md5sum $$k | sed -e "s/.\///" >> MD5SUMS; done; \
	cd ../admin; \
	echo "Processing admin"; \
	rm MD5SUMS; \
	for k in `find -type f | grep -v .svn`; do echo $$k; md5sum $$k | sed -e "s/.\///" >> MD5SUMS; done; \
	done

clean:
	rm -rf *.zip
