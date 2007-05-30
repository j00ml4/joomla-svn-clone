if [ $# -eq 1 ]; then
	echo $1
	if [ -e $1 ]; then
		xsltproc convert.xsl $1 > temp.xml
		mv temp.xml $1
	fi
fi	
