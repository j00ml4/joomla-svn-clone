<refentry id="{@id}">
 <refnamediv>
  <refname>JObject</refname>
 </refnamediv>
 <refsect1 id="{@id section}">
  <title>Description</title>
  <para>
   The JObject is your base class from which all other classes descend.
   It provides the functionality of having constructors and destructors
   defined using __construct() and __destruct().
  </para>
 </refsect1>
 <refsect1 id="{@id section}">
  <title>Joomla! Version</title>
  <para>
   This class was introduced in Joomla! 1.1.
  </para>
 </refsect1>
 <refsect1 id="{@id section}">
  <title>Defined in</title>
  <para>/libraries/joomla/common/base/object.php</para>
 </refsect1>
 <refsect1 id="{@id section}">
  <title>Functions</title>
  <memberlist>
   <member>
    <memberentry>JObject</memberentry>
    <memberdesc>
        Constructor for the JObject class. This constructor enables __construct
        and __destruct to be used as constructors and destructors in PHP 4.
    </memberdesc>
   </member>
   <member>
    <memberentry>__construct</memberentry>
    <memberdesc>This is an empty constructor that is overriden in descendant classes.</memberdesc>
   </member>
   <member>
    <memberentry>__destruct</memberentry>
    <memberdesc>This is an empty destructor that is overriden in descendant classes.</memberdesc>
   </member>
   <member>
    <memberentry>get</memberentry>
    <memberdesc>This function gets the value of the object's specified property.</memberdesc>
   </member>
   <member>
    <memberentry>set</memberentry>
    <memberdesc>This functions sets the value of the object's specified property.</memberdesc>
   </member>
   <member>
    <memberentry>toString</memberentry>
    <memberdesc>
       This function is used to convert an object to a string. The
       base function returns the name of the class. Descendant classes
       can override this function as necessary.
    </memberdesc>
   </member>
  </memberlist>
 </refsect1>
</refentry>
