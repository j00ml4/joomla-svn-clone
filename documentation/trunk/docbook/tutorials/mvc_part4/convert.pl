#!/usr/bin/perl
# DocBook XML to MediaWiki Converter V. 0.0.2
# Many bugs fixed thanks to Ewout feedback
# (C) 2005 Stefano Selleri [GNU GPL License, look for it]
# Designed for migrating Blender DOC from DocBook to Mediawiki
# Use at your Own RISK!
$IMGPATH = shift ;

$xml = "";
while (<STDIN>) {
  #s/^[ ,\t]*//;
  $xml .= $_  
}
#print $xml;

$xml =~ s/&lt;/</sg;
$xml =~ s/&gt;/>/sg;
$xml =~ s/&amp;/&/sg;


$i = 0;

while($xml =~ s/<programlisting(.*?)>(.*?)<\/programlisting>/CODESEGMENT/s){
	$arr[$i] = $2;
	$arr[$i] =~ s/\s*<!\[CDATA\[\s*//sg;
	$arr[$i] =~ s/\s*\]\]>\s*//sg;
	$i++;
};

$xml =~ s/^[ ,\t]*//sg;
while ($xml =~ s/\t//sg) {};
while ($xml =~ s/  / /sg) {};

while($xml =~ s/\s{2,}/ /sg){};
while($xml =~ s/\|\n //sg){};
while($xml =~ s/ \n/\n/sg){};
while($xml =~ s/\n\n/\n/sg){};


$xml =~ s/\r//sg;
while($xml =~ /<xref(.*?)\/>/s) {
  $lab = $1;  $id = $lab;  $id =~ s/(.*?)=(.*?)/$2/s;
  $id =~ s/"//g;  while($id =~ s/^ //s){}  while($id =~ s/ $//s){}
  $xml =~ /id *= *" *$id(.*?)<title>(.*?)<\/title>/s;
  $cap = $2;
  if (length($cap)<2){$cap=$id;}
  $xml =~ s/<xref$lab\/>/''$cap''/sg;
}

$xml =~ s/<\?xml(.*?)\?>//sg;

$xml =~ s/<para>(.*?) '' (.*?)<\/para>/<para>$1 quotes $2<\/para>/sg;

$xml =~ s/<!--(.*?)-->//sg;
$xml =~ s/(.*?)<chapter(.*?)>(.*?)<title>(.*?)<\/title>/\n====== $4 ======\n/sg;
$xml =~ s/<\/chapter>//sg;
$xml =~ s/<para>/\n/sg;
$xml =~ s/<\/para>/\n\n/sg;

while ($xml =~ s/======\s*(\S+)\s{2,}(\S+)\s*======/====== $1 $3 ======\n/sg) {};
$xml =~ s/======(.*?)\n(.*?)======/====== $1 $2 ======\n/sg;

$xml =~ s/<section(.*?)<title>(.*?)<\/title>/==$2==/sg;
$xml =~ s/<section(.*?)>//sg;
$xml =~ s/<\/section>//sg;

$xml =~ s/<sect1(.*?)<title>(.*?)<\/title>/\n===== $2 =====\n/sg;
$xml =~ s/<sect1(.*?)>//sg;
$xml =~ s/<\/sect1>//sg;

$xml =~ s/<sect2(.*?)<title>(.*?)<\/title>/\n==== $2 ====\n/sg;
$xml =~ s/<sect2(.*?)>//sg;
$xml =~ s/<\/sect2>//sg;

$xml =~ s/<sect3(.*?)<title>(.*?)<\/title>/\n=== $2 ===\n/sg;
$xml =~ s/<sect3(.*?)>//sg;
$xml =~ s/<\/sect3>//sg;

$xml =~ s/<sect4(.*?)<title>(.*?)<\/title>/\n== $2 ==\n/sg;
$xml =~ s/<sect4(.*?)>//sg;
$xml =~ s/<\/sect4>//sg;

$xml =~ s/<sect5(.*?)<title>(.*?)<\/title>/\n= $2 =\n/sg;
$xml =~ s/<sect5(.*?)>//sg;
$xml =~ s/<\/sect5>//sg;

$xml =~ s/<simplelist>/\n/sg;
$xml =~ s/<\/simplelist>//sg;
$xml =~ s/<member>(.*?)<\/member>/\n  \* $1\n/sg;

$xml =~ s/<bridgehead(.*?)>(.*?)<\/bridgehead>/====$2====\n$3/sg;

$xml =~ s/\#__//sg;

$xml =~ s/<figure(.*?)<title>(.*?)<\/title>(.*?)<(.*?)fileref(.*?)\/([^\/]*?)"(.*?)<\/figure>/\[\[Image:$IMGPATH-$6|frame|none|$2\]\]/sg;
$xml =~ s/<guiicon(.*?)fileref(.*?)\/([^\/]*?)"(.*?)<\/guiicon>/\[\[Image:$IMGPATH-$3\]\]/sg;

$xml =~ s/<remark>(.*?)<\/remark>/''$1''/sg;

$xml =~ s/<filename>(.*?)<\/filename>/$1/sg;

$xml =~ s/<ulink(.*?)url="(.*?)">(.*?)<\/ulink>/[[$2|$3]]/sg;

$xml =~ s/<emphasis>(.*?)<\/emphasis>/''$1''/sg;
$xml =~ s/<literal>(.*?)<\/literal>/\{\{Literal|$1\}\}/sg;
$xml =~ s/\&(.?)KEY;/\{\{K|$1\}\}/sg;
$xml =~ s/<keycap>(.?)KEY<\/keycap>/\{\{K|$1\}\}/sg;
$xml =~ s/<keycap>(.*?)<\/keycap>/\{\{KEY|$1\}\}/sg;

$xml =~ s/<itemizedlist>//sg;
$xml =~ s/<\/itemizedlist>//sg;
$xml =~ s/<orderedlist>//sg;
$xml =~ s/<\/orderedlist>//sg;
$xml =~ s/<variablelist>//sg;
$xml =~ s/<\/variablelist>//sg;
$xml =~ s/<varlistentry>//sg;
$xml =~ s/<\/varlistentry>//sg;

$xml =~ s/<term>(.*?)<\/term>/====$1====/sg;
$xml =~ s/<listitem>/\*/sg;
#while($xml =~ s/\n\* *\n/\n\*/sg){};
$xml =~ s/<\/listitem>//sg;

$xml =~ s/<superscript>/<sup>/sg;
$xml =~ s/<\/superscript>/<\/sup>/sg;
$xml =~ s/<subscript>/<sup>/sg;
$xml =~ s/<\/subscript>/<\/sup>/sg;

$xml =~ s/<note>(.*?)<title>(.*?)<\/title>(.*?)<\/note>/\{\{Note|$2|$3\}\}/sg;
$xml =~ s/<note>(.*?)<\/note>/\{\{Note|Note|$1\}\}/sg;
$xml =~ s/<tip>(.*?)<title>(.*?)<\/title>(.*?)<\/tip>/\{\{Note|$2|$3\}\}/sg;
$xml =~ s/<tip>(.*?)<\/tip>/\{\{Note|A Tip|$1\}\}/sg;


#while($xml =~ s/\n //sg){};
while($xml =~ s/\|\n //sg){};
while($xml =~ s/ \n/\n/sg){};
while($xml =~ s/\n\n\n/\n\n/sg){};

$i = 0;


while($xml =~ s/CODESEGMENT/\n<code php>$arr[$i]<\/code>\n/s){
	$i++;
};


print $xml;

__END__

while($xml =~ /<tip>(.*?)<\/tip>/s) {
  $content = $1;
  $formatted = $content;
  if ($formatted =~ /<title>(.*?)<\/title>/) {
    $title = $1;
    $formatted =~ s/<title>$title<\/title>//s;
  } else {
    $title = "A Tip";
  }
  $formatted =~ s/\{\{K\|(.*?)\}\}/'''$1'''/sg;
  $formatted =~ s/\{\{KEY\|(.*?)\}\}/'''$1'''/sg;
  $formatted =~ s/\{\{Literal\|(.*?)\}\}/<tt>$1<\/tt>/sg;
  $formatted =~ s/\{//sg;
  $formatted =~ s/\}//sg;
  $content =~s/\(/\\\(/g;
  $content =~s/\)/\\\)/g;
  $content =~s/\?/\\\?/g;
  $content =~s/\//\\\//g;
  $content =~s/\*/\\\*/g;
  #print $content;
  $xml =~ s/<tip>$content<\/tip>/\{\{Tip|$title|$formatted\}\}/sg;

}

while($xml =~ /<note>(.*?)<\/note>/sg) {
  $content = $1;
  $formatted = $content;
  if ($formatted =~ /<title>(.*?)<\/title>/) {
    $title = $1;
    $formatted =~ s/<title>$title<\/title>//s;
  } else {
    $title = "Note";
  }
  $formatted =~ s/\{\{K\|(.*?)\}\}/'''$1'''/sg;
  $formatted =~ s/\{\{KEY\|(.*?)\}\}/'''$1'''/sg;
  $formatted =~ s/\{\{Literal\|(.*?)\}\}/<tt>$1<\/tt>/sg;
  $formatted =~ s/\{//sg;
  $formatted =~ s/\{//sg;
  $content =~s/\(/\\\(/g;
  $content =~s/\)/\\\)/g;
  $content =~s/\?/\\\?/g;
  $content =~s/\//\\\//g;
  $content =~s/\*/\\\*/g;
  $xml =~ s/<note>$content<\/note>/\{\{Note|$title|$formatted\}\}/sg;
}
