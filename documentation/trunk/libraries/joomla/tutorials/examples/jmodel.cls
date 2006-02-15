<refentry id="{@id}">
 <refnamediv>
  <refname>JModel</refname>
 </refnamediv>
 <refsect1 id="{@id section}">
  <title>Description</title>
  <para>The JModel is your workhorse class that allows you to easily map php objects to a database table.</para>
 </refsect1>
 <refsect1 id="{@id section}">
  <title>History</title>
  <history>
   <historyitem><historyversion>1.0</historyversion><historydescription>Class introduced as <classname>mosDBTable</classname></historydescription></historyitem>
   <historyitem><historyversion>1.1</historyversion><historydescription>Class refactored into <classname>JModel</classname>.</historydescription></historyitem>
  </history>
 </refsect1>
 <refsect1 id="{@id section}">
  <title>Defined in</title>
  <para>/libraries/joomla/model/model.php</para>
 </refsect1>
 <refsect1 id="{@id section}">
  <title>Functions</title>
  <memberlist>
   <member>
    <memberentry>__construct</memberentry>
    <memberdesc>
        Constructor for the <classname>JModel</classname> class. This constructor stores the table and key
        information and stores a reference to the database object.
    </memberdesc>
   </member>
   <member>
    <memberentry>getInstance</memberentry>
    <memberdesc>Returns a reference a <classname>JModel</classname> object. It always creates a new object.</memberdesc>
   </member>
   <member>
    <memberentry>filter</memberentry>
    <memberdesc>Filters public properties. Allows certain members to be ignored.</memberdesc>
   </member>
   <member>
    <memberentry>getError</memberentry>
    <memberdesc>Returns the error message.</memberdesc>
   </member>
   <member>
    <memberentry>getPublicProperties</memberentry>
    <memberdesc>Returns an array of public properties.</memberdesc>
   </member>
   <member>
    <memberentry>bind</memberentry>
    <memberdesc>
       Binds a named array/hash to this object.
    </memberdesc>
   </member>
   <member>
    <memberentry>load</memberentry>
    <memberdesc>
       Binds an array/hash to this object.
    </memberdesc>
   </member>
   <member>
    <memberentry>check</memberentry>
    <memberdesc>
       Generic check method. Used to check the validity of the data in the object.
    </memberdesc>
   </member>
   <member>
    <memberentry>store</memberentry>
    <memberdesc>
       Inserts a new row if id is zero or updates an existing row in the database table.
    </memberdesc>
   </member>
   <member>
    <memberentry>move</memberentry>
    <memberdesc>
       Moves a record up or down in the table ordering.
    </memberdesc>
   </member>
   <member>
    <memberentry>updateOrder</memberentry>
    <memberdesc>
       Compacts the ordering sequence of the selected records.
    </memberdesc>
   </member>
  </memberlist>
   <member>
    <memberentry>etc...</memberentry>
    <memberdesc>
       etc etc etc...
    </memberdesc>
   </member>
 </refsect1>
</refentry>
