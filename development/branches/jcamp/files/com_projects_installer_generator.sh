cd /Volumes/WORK/svn/jsop/jcamp/
rm -R files/com_projects
rm -R files/com_projects.tar.gz

mkdir files/com_projects
mkdir files/com_projects/components
mkdir files/com_projects/language
mkdir files/com_projects/language/en-GB
mkdir files/com_projects/administrator
mkdir files/com_projects/administrator/components
mkdir files/com_projects/administrator/language
mkdir files/com_projects/administrator/language/en-GB
mkdir files/com_projects/media

cp -R components/com_projects files/com_projects/components
cp language/en-GB/en-GB.com_projects.ini files/com_projects/language/en-GB
cp -R administrator/components/com_projects files/com_projects/administrator/components
cp administrator/language/en-GB/en-GB.com_projects.ini files/com_projects/administrator/language/en-GB
cp administrator/language/en-GB/en-GB.com_projects.sys.ini files/com_projects/administrator/language/en-GB
cp -R media/com_projects files/com_projects/media

mv files/com_projects/administrator/components/com_projects/projects.xml files/com_projects
find files/com_projects -name ".svn" | xargs rm -Rf
tar -cvf files/com_projects.tar.gz files/projects
rm -R files/com_projects