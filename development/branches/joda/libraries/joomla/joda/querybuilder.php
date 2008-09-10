<?php
/**
 * @version     $Id$
 *
 * @package     Joomla.Framework
 * @subpackage  Joda
 *
 * @copyright    Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 */

/**
 * Check to ensure this file is within the rest of the framework
 */
defined( 'JPATH_BASE' ) or die();

//TODO: Perhaps delete(), select(), insert(), etc. should RESET the query ??
//TODO: Use quoting methods from PDO ?


/**
 * JQueryBuilder Base class
 *
 * @package     Joomla.Framework
 * @subpackage  Joda
 */
abstract class JQueryBuilder extends JObject
{

    /**
     * Default name for subselects if not specified
     * @var string
     */
    const DEFAULT_SUBSELECT_NAME = 'SUBSELECT1';

    // Glues
    const GLUEOR = 'OR';
    const GLUEAND = 'AND';

    // Query Types
    const QT_NONE         = 'QT_NONE';
    const QT_SELECT     = 'QT_SELECT';
    const QT_SUBSELECT     = 'QT_SUBSELECT';
    const QT_UPDATE     = 'QT_UPDATE';
    const QT_INSERT     = 'QT_INSERT';
    const QT_DELETE     = 'QT_DELETE';

    // Common/Standard Query Sections
    const QS_SELECT     = 'QS_SELECT';
    const QS_UPDATE     = 'QS_UPDATE';
    const QS_DELETE     = 'QS_DELETE';
    const QS_INSERT     = 'QS_INSERT';
    const QS_FROM         = 'QS_FROM';
    const QS_WHERE         = 'QS_WHERE';
    const QS_GROUPBY     = 'QS_GROUPBY';
    const QS_HAVING     = 'QS_HAVING';
    const QS_ORDERBY     = 'QS_ORDERBY';
    const QS_FORUPDATE     = 'QS_FORUPDATE';
    const QS_LIMIT         = 'QS_LIMIT';
    const QS_FIELDVALUES = 'QS_FIELDVALUES';
    const QS_DISTINCT     = 'QS_DISTINCT';
    const QS_FIELDS     = 'QS_FIELDS';
    const QS_VALUES     = 'QS_VALUES';

    // Atom types
    const QA_FROMATOM     = 'QA_FROMATOM';     // <from-item>
    const QA_JOINATOM    = 'QA_JOINATOM';     // JOIN <from-item>
    const QA_EXPR         = 'QA_EXPR';         // expression
    const QA_ORLP         = 'QA_ORLP';         // 'OR ('
    const QA_LP         = 'QA_LP';             // '('
    const QA_RP         = 'QA_RP';             // ')'




    /**
     * SELECT Template
     * @var string
     */
    const SELECT_TPL = "SELECT %%QS_DISTINCT%% %%QS_SELECT%% %%QS_FROM%% %%QS_WHERE%% %%QS_GROUPBY%% %%QS_ORDERBY%% %%QS_LIMIT%%";

    /**
     * UPDATE Template
     * @var string
     */
    const UPDATE_TPL = "UPDATE %%QS_UPDATE%% %%QS_FIELDVALUES%% %%QS_WHERE%%";

    /**
     * INSERT INTO Template
     * @var string
     */
    const INSERT_TPL = "INSERT INTO %%QS_INSERT%% %%QS_FIELDS%% %%QS_VALUES%%";

    /**
     * DELETE Template
     * @var string
     */
    const DELETE_TPL = "DELETE %%QS_FROM%% %%QS_WHERE%% %%QS_LIMIT%%";


    /**
     * Array of templates. Later enumerated to self-describe all possible SQL sections
     * @var array
     */
    protected $_SQLTemplates = array(
        "QT_NONE" => '',
        "QT_SELECT"     => self::SELECT_TPL,
        "QT_SUBSELECT"  => self::SELECT_TPL,
        "QT_UPDATE"     => self::UPDATE_TPL,
        "QT_INSERT"     => self::INSERT_TPL,
        "QT_DELETE"     => self::DELETE_TPL
        );




    /**
     * Array of ALL possible sections for current SQL dialect
     * @var array
     */
    protected $_sections = array();

    /**
     * Array of type related sections (SELECT.. FROM.. WHERE.. LIMIT.. etc.)
     * @var array
     */
    protected $_typeSectionsList = array();

    /**
     * Array of ALL section's SQL code
     * @var array
     */
    protected $_sectionsSQL = array();


    /**
     * This query type (SELECT, UPDATE, INSERT, DELETE, etc.)
     * @var string
     */
    protected $_type = self::QT_NONE;

    /**
     * The subselect name/alias in case of SUBSELECT
     * @var string
     */
    protected $_subselectname = self::DEFAULT_SUBSELECT_NAME;

    /**
     * If it is a SELECT, is it DISTINCT ??
     * @var boolean
     */
    protected $_distinct = false;


    /**
     * Current section query builder is working on.
     *
     * This is needed to decide where to put parenthesis added by (possible) consequent LP()/RP() call.
     * Methods LP() and RP() are used for both WHERE and HAVING sections and any other section
     * where [logical] expressions appear or requires grouping (JOIN, for example). Not using something
     * like _cursection leads to a need for more LP()-like methods, for every section having expressions with
     * possible use of parenthesis. Hope this make sense :-)
     *
     * Every call to where() or having() whatsoever sets the _cursection to corresponding section value
     * (QS_WHERE or QS_HAVING, etc.).
     *
     * @var string
     */
    protected $_cursection = null;

    /**
     * DEFAULT string for default values in INSERT and UPDATE
     *
     * @var string
     */
    protected $_sDefault = 'DEFAULT';

    /**
     * List of SQL reserved keywords
     *
     * @var array
     */
    protected $_reservedKeywords = array();

    /**
     * Name Quoting characters for identifiers
     *
     * @var array
     */
    protected $_name_quotes = array("");

    /**
     * Quoting characters for text literals
     *
     * @var array
     */
    protected $_text_quotes = array("");


    /**
     * Constructor
     *
     * @return object JQueryBuilder
     */
    function __construct(  )
    {
        //
    }


    /**
     * Magic __toString() function (see PHP Manual)
     *
     * @return string
     */
    function __toString()
    {
    	return $this->getSQL();
    }


    /******************************************************************/
    /******************** PROTECTED METHODS ***************************/
    /******************************************************************/


    /**
     * Convert a string to a single element array of strings.
     *
     * @param string
     * @return array Array of strings
     */
    protected function _strToArray($input)
    {
        if ( is_string( $input ) ) $input = array($input);
        return $input;
    }


    /**
     * Add <jointype> JOIN <fromitem>
     *
     * @param string  From item (table or sub-select)
     * @param string  (optional) JOIN condition
     * @param string  (optional) Alias/Correlation
     * @param string  (optional) JOIN type (CROSS, INNER, LEFT, etc)
     * @return object JQueryBuilder
     */
    protected function _join( $fromitem, $condition = '', $alias = '', $jointype = '' )
    {

        $this->_cursection = self::QS_FROM;

        $item = array(  "atomtype" => self::QA_JOINATOM,
                        "fromitem" => $fromitem,
                        "condition" => $condition,
                        "alias" => $alias,
                        "jointype" => $jointype );
        $this->_sections[self::QS_FROM][] = $item;
        return $this;
    }


    /**
     *  Returns an array of array of expressions used for WHERE and HAVING sections
     *  along with info if they are logically ANDed or ORed and
     *  the type of outer glue to use: AND or OR.
     *
     *  <var>Outer glue</var>: the glue to use to concatenate the current expression
     *  with the previous one, if any.
     *
     *  Example: (a=b) <outerglue> ((c=d)<innerglue> (e=f))
     *
     * @param mixed   Expression(s)  (string or array of strings)
     * @param string  Inner glue
     * @param string  Outer glue
     * @param string  Atom type (an Expression or a Parenthesis)
     * @return array Array of expressions
     */
    protected function _expression( $input, $innerglue = self::GLUEAND, $outerglue = self::GLUEAND, $atomtype)
    {
        $input = $this->_strToArray($input);
        $result = array();
        if ( is_array($input) ) {
            $item = array(
                            "atomtype" => $atomtype,
                            "expressions" => $input,
                            "innerglue" => $innerglue,
                            "outerglue" => $outerglue
                             );
            $result[] = $item;
        }
        return $result;
    }


    /**
     *  Generic where() to add expression to WHERE section.
     *  Sets the current section we are working on, so that LP()
     *  and RP() /if any/ know about it.
     *
     * @param string|array  Expression(s)
     * @param string Inner glue
     * @param string Outer glue
     * @return object JQueryBuilder
     */
    protected function _where( $input, $innerglue = self::GLUEAND, $outerglue = self::GLUEAND )
    {
        $this->_cursection = self::QS_WHERE;

        if ( $input == null ) return $this;

        $tmp = $this->_expression( $input, $innerglue, $outerglue, self::QA_EXPR );
        if ( ! isset($this->_sections[self::QS_WHERE]) ) {
            $this->_sections[self::QS_WHERE] = array();
        }
        $this->_sections[self::QS_WHERE] = array_merge( $this->_sections[self::QS_WHERE], $tmp );
        return $this;
    }


    /**
     *  Add parethesis to the current section!
     *
     * @param string  Parenthesis type (LP, orLP, RP)
     * @return object JQueryBuilder
     */
    protected function _parenthesis( $parenth )
    {
        $item = array( "atomtype" => $parenth );
        $this->_sections[$this->_cursection][] = $item;
        return $this;
    }


    /**
     * Convert expression section data to expression SQL string
     *
     * @param array Expression data
     * @return string Expression string
     */
    protected function _expression_toString( $input )
    {
        if ( count($input) <= 0 ) {
            return '';
        }

        $tmp = '';
        reset($input);
        foreach ( $input as $item ) {
            $atomtype = $item["atomtype"];
            if ( $atomtype == self::QA_EXPR ) {
                $innerglue = $item["innerglue"];
                $outerglue = $item["outerglue"];
                $expressions = $item["expressions"];
                $items = array();

                // Handle parenthesis and glue issues
                if ( count($expressions) > 1 ) {
                    $tmplp = '(';
                    $tmprp = ')';
                } else {
                    $tmplp = $tmprp = '';
                }

                foreach ( $expressions as $expression ) {
                    $items[] = $tmplp.$expression.$tmprp;
                }

                // Can we put an Outer glue??
                $sofar = trim( $tmp );
                $lastchar = substr( $sofar, -1, 1 );

                if ( $lastchar == ')' ) {
                    $tmpglue = ' '.$outerglue.' ';
                } else {
                    $tmpglue = '';
                }

                $tmplp = '(';
                $tmprp = ')';

                $tmp .= $tmpglue.$tmplp.implode( ' '.$innerglue.' ', $items ).$tmprp;
            } else if ( ($atomtype == self::QA_LP) || ($atomtype == self::QA_ORLP) ) {
                $sofar = trim( $tmp );
                $lastchar = substr( $sofar, -1, 1 );

                // assuming every expression is enclosed in parenthesis
                if ( $lastchar == ')' ) {
                    $tmp .= ( $atomtype == self::QA_ORLP ) ? ' OR ' : ' AND ';
                }
                $tmp .= '(';
            } else if ( $atomtype == self::QA_RP ) {
                $tmp .= ')';
            }
        }

        return $tmp;
    }


    /**
     * Cpnvert SELECT-type section to string
     *
     * @param array Select data
     * @param boolean False/Tru: enclose in (...) ?
     * @return string Select list
     */
    protected function _selectlist_toString( $input, $brackets=false )
    {
        $tmp = '';

        if ( count($input) <= 0 ) {
            return $tmp;
        }

        $items = array();
        foreach ( $input as $item ) {
            $tmp = $item["expression"];
            if ( ! empty($item["alias"]) ) {
                $tmp .= ' AS '.$item["alias"];
            }
            $items[] = $tmp;
        }
        $result = implode( ', ', $items );
        if ($brackets) {
            $result = '(' . $result . ')';
        }
        return $result;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_select_toString( $section )
    {
        $this->_sectionsSQL[$section] = $this->_selectlist_toString( $this->_sections[$section] );
        return true;
    }


    /**
     * Build SQL piece of code
     *
     *
     * @param string Section name
     * @return boolean
     *
     */
    protected function _qs_from_toString( $section )
    {
        $input = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';

        if ( count($input) <= 0 ) {
            return false;
        }

        $items = array();
        foreach ( $input as $item ) {
            // a FROM type atom, i.e. a table or sub-select
            if ( $item["atomtype"] == self::QA_FROMATOM ) {
                $tmp = $item["fromitem"];
                if ( ! empty($item["alias"]) ) {
                    $tmp .= ' AS '.$item["alias"];
                }
                $items[] = count( $items ) > 0 ? ', '.$tmp : $tmp;
            }


            // Left/Right parenthesis
            if ( ($item["atomtype"] == self::QA_LP) ) {
                $items[]= '(';
            }
            if ( $item["atomtype"] == self::QA_RP ) {
                $items[]= ')';
            }

            // a JOIN type atom, i.e. table or sub-select to join
            if ( $item["atomtype"] == self::QA_JOINATOM ) {
                $tmp = $item["jointype"].' '.$item["fromitem"];
                if ( ! empty($item["alias"]) ) {
                    $tmp .= ' AS '.$item["alias"];
                }
                if ( ! empty($item["condition"]) ) {
                    $tmp .= ' ON ('.$item["condition"].')';
                }
                $items[] = $tmp;
            }
        }

        $this->_sectionsSQL[$section] = 'FROM '.implode( ' ', $items );
        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_where_toString( $section )
    {
        $input = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';
        $tmp = trim( $this->_expression_toString($input) );
        if ( ! empty($tmp) ) {
            $this->_sectionsSQL[$section] = 'WHERE '.$tmp;
        }
        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_limit_toString( $section )
    {
        $input = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';

        if ( count($input) <= 0 ) {
            return false;
        }
        $tmp = $this->sqlLimit( $input["limit"], $input["offset"] );

        $this->_sectionsSQL[$section] = $tmp;
        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_update_toString( $section )
    {
        $input = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';

        if ( count($input) <= 0 ) {
            return false;
        }
        $this->_sectionsSQL[$section] = trim( $input[0] );
        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_distinct_toString( $section )
    {
        $this->_sectionsSQL[$section] = '';
        if ( $this->_distinct ) {
            $this->_sectionsSQL[$section] = 'DISTINCT';
        }
        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_fields_toString( $section)
    {
        $this->_sectionsSQL[$section] = $this->_selectlist_toString( $this->_sections[$section] , true);
        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_values_toString( $section )
    {
        $input = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';

        if ( count($input) <= 0 ) {
            return false;
        }
        $tuples = array();
        foreach ( $input as $tuple ) {
            $tuples[] = '(' . implode(', ', $tuple) . ')';
        }
        $this->_sectionsSQL[$section] = ' VALUES ' . implode(', ', $tuples);
        return true;
    }





    /**
     * Build SQL piece of code
     *
     * @param string Section name
     *
     * NOTE: about DEFAULT values: so far MySQL and PgSQL are OK, but
     * soon this function might become abstract
     *
     * @return boolean
     *      *
     */
    protected function _qs_fieldvalues_toString( $section )
    {

        $type = $this->_type;
        $fieldvalues = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';


        $fields = array();
        $values = array();
        $result = '';

        if ( (count($fieldvalues) > 0) ) {
            if ( $type == self::QT_INSERT ) {
                foreach ($fieldvalues as $field => $value) {
                    if ( is_numeric($field) ) {
                        $fields[] = $value;
                        $values[] = $this->_sDefault;
                    } else {
                        $fields[] = $field;
                        $values[] = $value;
                    }
                }
                $result .= " (".implode( ", ", $fields ).") ";
                $result .= " VALUES (".implode( ", ", $values ).") ";
            } else if ( $type == self::QT_UPDATE ) {
                $items = array();
                if ( count($fieldvalues) > 0 ) {
                    foreach ( $fieldvalues as $field => $value ) {
                        if ( is_numeric($field) ) {
                            $field = $value;
                            $value = $this->_sDefault;
                        }
                        $items[] = "".$field."=".$value;
                    }
                    $result .= " SET ".implode( ", ", $items );
                }
            }
        }

        $this->_sectionsSQL[$section] = $result;
        return true;

    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_groupby_toString( $section )
    {
        $items = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';

        if ( count($items) <= 0 ) {
            return false;
        }

        $this->_sectionsSQL[$section] = 'GROUP BY '.implode( ', ', $items );
        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_orderby_toString( $section )
    {

        $input = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';

        if ( count($input) <= 0 ) {
            return false;
        }

        $result = '';
        $items = array();

        foreach ( $input as $item ) {
            $direction = '';
            if ( ! empty($item["direction"]) ) {
                $direction = ($item["direction"] == Joda::SORT_DESC) ? "DESC" : "ASC";
            }
            $items[] = $item["expression"]." ".$direction;
        }

        if ( count($items) > 0 ) {
            $result = implode( ', ', $items );
            $this->_sectionsSQL[$section] = 'ORDER BY '.$result;
        }

        return true;
    }


    /**
     * Build SQL piece of code
     *
     * @param string Section name
     * @return boolean
     */
    protected function _qs_insert_toString( $section )
    {
        $input = $this->_sections[$section];

        $this->_sectionsSQL[$section] = '';

        if ( count($input) <= 0 ) {
            return false;
        }
        $this->_sectionsSQL[$section] = trim( $input[0] );
        return true;
    }



    /**
     * Tricky function to warn developers to define needed class constants and methods
     * based on the query template sections.
     *
     * @return array Array of errors, if any
     */
    protected function _checkDevJob()
    {
        $errors = array();
        reset( $this->_sections );
        foreach ( $this->_sections as $section => $value ) {
            $classname = get_class( $this );
            $method = "_".strtolower( $section )."_toString";
            $constant = $classname.'::'.$section;
            if ( (! defined($constant)) || (! method_exists($this, $method)) ) {
                $errors[] = "Class/Section: ".$classname." / ".$section;
            }
        }
        if ( count($errors) <= 0 ) {
            $errors = false;
        }
        return $errors;
    }


    /**
     *  Generic function to add SELECT item(s)
     *
     * @param mixed   Item(s) to add (string or array)
     * @param string  Section name to add the item(s) to.
     * @return boolean
     */
    protected function _select( $input, $section, $aliased=true )
    {
        $input = $this->_strToArray($input);
        if ( is_array($input) ) {
            foreach ( $input as $alias => $expression ) {
                $alias = trim( $alias );
                $expression = trim( $expression );
                if ( is_numeric($alias) || ! $aliased) {
                    $alias = '';
                }
                $item = array( "alias" => $alias, "expression" => $expression );
                $this->_sections[$section][] = $item;
            }
        }
        return true;
    }


    /**
     * Initialize sections.
     *
     * @return object JQueryBuilder
     */
    protected function _initSections()
    {
        // Discover supported SQL sections matching all
        // %%<SECTION-NAME>%%  patterns in all SQL templates
        $matches = array();
        $sections = array();
        $this->_sections = array(); // union, that is, ALL sections
        reset($this->_SQLTemplates);
        foreach ( $this->_SQLTemplates as $type => $template ) {
            $this->_typeSectionsList[$type] = array();
            $n = preg_match_all( "/%%([^%]+)%%/", $template, $matches, PREG_PATTERN_ORDER );
            if ( $n > 0 ) { // found something ??
                foreach ( $matches[1] as $section ) {
                    $sections[] = $section;
                    $this->_typeSectionsList[$type][] = $section;
                }
                $this->_typeSectionsList[$type] = array_unique( $this->_typeSectionsList[$type] );
            }
        }

        $sections = array_unique( $sections );
        reset( $sections );
        foreach ( $sections as $section ) {
            $this->_sections[$section] = array();
            $this->_sectionsSQL[$section] = '';
        }

        // Check this class about needed constants and methods to handle this very section.
        // This must be resolved at development stage so suggested behaviour is to
        // halt the script!!!!
        if ( ($errors = $this->_checkDevJob()) != false ) {
            echo "Undefined class constant and/or method for sections: <BR>";
            foreach ( $errors as $error ) {
                echo $error."<BR>";
            }
            die( '<P><font color=red>Developers, please do your job!</font></P>' );
        }


        return $this;
    }


    /**
     * Transform Sections data to SQL Piece of Code
     * Ignore sections irrelevant to current query type.
     *
     */
    protected function _sections_toString()
    {
        reset( $this->_sectionsSQL );
        foreach ( $this->_sectionsSQL as $section => $value ) {
            // Handle only current query type sections
            if ( in_array($section, $this->_typeSectionsList[$this->_type]) ) {
                $method = "_".strtolower( $section )."_toString";
                $this->$method( $section );
            }
        }
    }




    /******************************************************************/
    /******************** PUBLIC METHODS ******************************/
    /******************************************************************/



    /**
     * Returns a reference to a Query Builder object
     *
     * @param string SQL dialect name
     * @return object JQueryBuilder
     */
    public function &getInstance($sqldialect)
    {
        $path = dirname(__FILE__) .DS. 'querybuilder' .DS. $sqldialect . '.php';
        require_once($path);
        $class = "JQueryBuilder".$sqldialect;
        $instance =  new $class();
        return $instance;
    }




    /**
     * Reset query to its default state and data
     *
     * @return object JQueryBuilder
     *
     */
    public function resetQuery()
    {
        $this->_initSections();
        $this->_type = self::QT_NONE;
        $this->_distinct = false;
        return $this;
    }


    /**
     * Reset a single section
     *
     * @param string  The section to reset
     * @return object JQueryBuilder
     *
     */
    public function resetSection( $section = null )
    {
        if ( ! isset($section) ) {
            return $this;
        }
        $this->_sections[$section] = array();
        return $this;
    }




    /**
     * Return the Current section.
     *
     */
    public function getCurSection()
    {
        return $this->_cursection;
    }


    /**
     * Generate a single SQL Statement
     *
     * @param string  An optional, "last minute given" Subselect name
     * @return string SQL Statement
     *
     */
    protected function _toString( $subselectname = '' )
    {
        $sql = "";

        // Convert section data to SQL strings
        $this->_sections_toString();

        // Pick the SQL query template
        $query_tpl = $this->_SQLTemplates[$this->_type];
        $sql = $query_tpl;

        // Iterate over all known sections and apply values to SQL Template
        reset( $this->_sectionsSQL );
        foreach ( $this->_sectionsSQL as $section => $value ) {
            $sql = str_replace( "%%".$section."%%", $value, $sql );
        }

        // SUBSELECT Query Types need one more thing
        if ( $this->_type == self::QT_SUBSELECT ) {
            $sql = $this->sqlSubselect( $sql, $subselectname );
        }

        return $sql;
    }




    /**
     *  Add a SELECT expression(s), i.e. the <selectlist>
     *
     * Input can be null; in that case '*' is considered by default as SELECT caluse
     *
     * @param string|array  Expressions (string or array)
     * @return object JQueryBuilder
     *
     */
    public function select( $input = null )
    {
        if ( $this->_type != self::QT_SELECT ) {
            $this->resetQuery();
        }
        $this->_type = self::QT_SELECT;
        if ( isset($input) ) {
            $this->_select( $input, self::QS_SELECT, true  );
        }
        else {
        	$this->_select( "*", self::QS_SELECT, true  );
        }
        return $this;
    }


    /**
     *  Add a SELECT expression(s) to a SUBSELECT. Mimics the select()
     *
     * @param mixed  Expressions (string or array)
     * @param string  (optional) The subselect name
     * @return object JQueryBuilder
     *
     */
    public function subselect( $input, $subselectname = null )
    {
        if ( $this->_type != self::QT_SUBSELECT ) {
            $this->resetQuery();
        }

        if ( ! empty($subselectname) ) {
            $this->_subselectname = $subselectname;
        }

        $this->_type = self::QT_SUBSELECT;
        $this->_select( $input, self::QS_SELECT );
        return $this;
    }


    /**
     * Set the query Distinct property
     *
     * Examples
     *     distinct() = DISTINCT ON
     *     distinct(true) = DISTINCT ON
     *     distinct(false) = DISTINCT OFF
     *     distinct(0) = DISTINCT OFF

     * @param boolean  If null or true, set DISTINCT ON, if false - DISTINCT OFF
     * @return boolean
     *
     *      */
    public function distinct( $distinct = null )
    {
        if ( (! isset($distinct)) || (true == $distinct) ) {
            $this->_distinct = true;
        } else {
            $this->_distinct = false;
        }
        return $this;
    }


    /**
     *  Add <froitems>, namely tables with aliases (if any)
     *
     * @param mixed  Tables (string or array)
     * @return object JQueryBuilder
     */
    public function from( $input )
    {
        $this->_cursection = self::QS_FROM;

        // Assuming an array of  'alias' => <fromitem>  OR just <fromitem>
        $input = $this->_strToArray($input);
        if ( is_array($input) ) {
            foreach ( $input as $alias => $fromitem ) {
                $alias = trim( $alias );
                $fromitem = trim( $fromitem );

                // Non-associative array = empty alias
                if ( is_numeric($alias) ) {
                    $alias = '';
                }
                $item = array(
                        "atomtype" => self::QA_FROMATOM,
                        "alias" => $alias,
                        "fromitem" => $fromitem );
                $this->_sections[self::QS_FROM][] = $item;
            }
        }
        return $this;
    }


    /**
     * Add <default-join-type> JOIN <fromitem>
     * Different RDBMS interpret the single JOIN differently.
     * In MySQL, JOIN is a shortcut for "[CROSS] JOIN", while in PgSQL it is "[INNER] JOIN".
     * Calls Dialect sqlJoin(<jointype>).
     *
     * @param string  From Item (table or sub-select)
     * @param string  (optional) JOIN condition
     * @param string  (optional) Alias/Correlation
     * @return object JQueryBuilder
     */
    public function join( $fromitem, $condition = '', $alias = '' )
    {
        $this->_join( $fromitem, $condition, $alias, $this->sqlJoin() );
        return $this;
    }

    /**
     * Add INNER JOIN <fromitem>
     *
     * @param string  From Item (table or sub-select)
     * @param string  (optional) JOIN condition
     * @param string  (optional) Alias/Correlation
     * @return object JQueryBuilder
     */
    public function innerjoin( $fromitem, $condition = '', $alias = '' )
    {
        $this->_join( $fromitem, $condition, $alias, $this->sqlJoin("INNER") );
        return $this;
    }


    /**
     * Add RIGHT JOIN <fromitem>
     *
     * @param string  From Item (table or sub-select)
     * @param string  (optional) JOIN condition
     * @param string  (optional) Alias/Correlation
     * @return object JQueryBuilder
     *
     */
    public function rightjoin( $fromitem, $condition = '', $alias = '' )
    {
        $this->_join( $fromitem, $condition, $alias, $this->sqlJoin("RIGHT") );
        return $this;
    }

    /**
     * Add LEFT JOIN <fromitem>
     *
     * @param string  From Item (table or sub-select)
     * @param string  (optional) JOIN condition
     * @param string  (optional) Alias/Correlation
     * @return object JQueryBuilder
     */
    public function leftjoin( $fromitem, $condition = '', $alias = '' )
    {
        $this->_join( $fromitem, $condition, $alias, $this->sqlJoin("LEFT") );
        return $this;
    }


    /**
     * Add a CROSS JOIN <fromitem>
     *
     * @param string  From Item (table or sub-select)
     * @param string  (optional) JOIN condition
     * @param string  (optional) Alias/Correlation
     * @return object JQueryBuilder
     */
    public function crossjoin( $fromitem, $condition = '', $alias = '' )
    {
        $this->_join( $fromitem, $condition, $alias, $this->sqlJoin("CROSS") );
        return $this;
    }


    /**
     * NOTE:
     * "where" method's names follow the rule:
     * <outerglue>where<innerglue>
     *
     */



    /**
     *  Add an expressions to the WHERE section.
     *  Inner and Outer glue = AND  (most used)
     *  (a=b) AND ((c=d) AND (e=f))
     *
     *  If called with NO arguments just sets the current section to QS_WHERE
     *
     * @param string|array  (optional) Expression(s)  (string or array)
     * @return object JQueryBuilder
     *
     */
    public function where( $input = null )
    {
        $this->_where( $input, self::GLUEAND, self::GLUEAND );
        return $this;
    }


    /**
     *  Add anexpressions to the WHERE section.
     *  Inner glue = OR and Outer glue = AND
     *  (a=b) AND ((c=d) OR (e=f))
     *
     * @param string|array  (optional) Expression(s)  (string or array)
     * @return object JQueryBuilder
     *
     */
    public function whereor( $input = null )
    {
        $this->_where( $input, self::GLUEOR, self::GLUEAND );
        return $this;
    }


    /**
     *  Add an expressions to the WHERE section.
     *  Inner glue = AND and Outer glue = OR
     *  (a=b) OR ((c=d) AND (e=f))
     *
     * @param mixed  (optional) Expression(s)  (string or array)
     * @return object JQueryBuilder
     *
     */
    public function orwhere( $input = null )
    {
        $this->_where( $input, self::GLUEAND, self::GLUEOR );
        return $this;
    }


    /**
     *  Add an expressions to the WHERE section.
     *  Inner and Outer glue = OR
     *  (a=b) OR ((c=d) OR (e=f))
     *
     * @param mixed  (optional) Expression(s)  (string or array)
     * @return object JQueryBuilder
     *
     */
    public function orwhereor( $input = null )
    {
        $this->_where( $input, self::GLUEOR, self::GLUEOR );
        return $this;
    }


    /**
     * Add Left Parenthesis.
     * When rendered to string add OR in front if needed
     *
     * @return object JQueryBuilder
     *
     */
    public function orlp()
    {
        $this->_parenthesis( self::QA_ORLP );
        return $this;
    }

    /**
     * Add Left Parenthesis.
     * When rendered add AND in front if needed (most used)
     *
     * @return object JQueryBuilder
     *
     */
    public function lp()
    {
        $this->_parenthesis( self::QA_LP );
        return $this;
    }


    /**
     * Add Right Parenthesis
     *
     * @return object JQueryBuilder
     *
     */
    public function rp()
    {
        $this->_parenthesis( self::QA_RP );
        return $this;
    }


    /**
     * Add GROUP BY expression
     *
     * @param mixed  (optional) Group by statements (string or array)
     * @return object JQueryBuilder
     *
     */
    public function groupBy( $input = null )
    {
        $input = $this->_strToArray($input);
        if ( is_array($input) ) {
            foreach ( $input as $item ) {
                $this->_sections[self::QS_GROUPBY][] = $item;
            }
        }
        return $this;
    }


    /**
     * Add ORDER BY
     *
     * @param mixed  (optional) Order by statements (string or array)
     * @return object JQueryBuilder
     *
     */
    public function orderBy( $input = null )
    {
        $input = $this->_strToArray($input);

        if ( is_array($input) ) {
            foreach ( $input as $expression => $direction ) {
                $expression = trim( $expression );
                $direction = trim( $direction );
                if ( is_numeric($expression) ) {
                    $item = array( "expression" => $direction, "direction" => 'ASC' );
                } else {
                    $item = array( "expression" => $expression, "direction" => $direction );
                }
                $this->_sections[self::QS_ORDERBY][] = $item;
            }
        }
        return $this;
    }


    /**
     * Add LIMIT information
     *
     * @param integer  The limit
     * @param integer  (optional) The offset
     * @return object JQueryBuilder
     *
     */
    public function limit( $limit, $offset = 0 )
    {
        $this->_sections[self::QS_LIMIT] = array( "limit" => $limit, "offset" => $offset );
        return $this;
    }


    /**
     * Set UPDATE section for UPDATE query type
     *
     * @param string   Table name
     * @return object JQueryBuilder
     *
     */
    public function update( $table )
    {
        $this->resetQuery();
        $this->_type = self::QT_UPDATE;
        $this->_sections[self::QS_UPDATE] = array( $table );
        return $this;
    }


    /**
     * Start INSERT-type query and set the table name
     *
     * @param string Table name to insert data into
     * @return object JQueryBuilder
     */
    public function insertinto( $table )
    {
        $this->resetQuery();
        $this->_type = self::QT_INSERT;

        $this->_sections[self::QS_INSERT] = array( $table );
        return $this;
    }


    /**
     * Add field list
     *
     * @param array|string  Array of field names or plain string
     * @return object JQueryBuilder
     *
     */
    public function fields( $input )
    {
        $aliased = ($this->_type == self::QT_SELECT);
        $this->_select( $input, self::QS_FIELDS, $aliased);
        return $this;
    }


    /**
     * Add a tuple to VALUES section.
     *
     * @param mixed  Array of values or plain string
     * @return object JQueryBuilder
     */
    public function values( $input )
    {
        $input = $this->_strToArray($input);
        if ( is_array( $input ) && (count($input) > 0) ) {
            $this->_sections[self::QS_VALUES][] = $input;
        }
        return $this;
    }


    /**
     * Values for UPDATE and INSERT query type
     *
     * @param array  Associatice array of "fieldname"->"vield value"
     * @return object JQueryBuilder
     */
    public function fieldvalues( $input )
    {
        if ( (is_array($input)) && (count($input) > 0) ) {
            $this->_sections[self::QS_FIELDVALUES] = array_merge( $this->_sections[self::QS_FIELDVALUES], $input );
        }
        return $this;
    }



    /**
     * Start a DELETE Query type
     * @return boolean
     */
    public function delete()
    {
        $this->resetQuery();
        $this->_type = self::QT_DELETE;
        return $this;
    }


    /**
     * Quote an identifier name (field, table, etc)
     *
     * @param   string The name
     * @return  string The quoted name
     */
    function nameQuote( $s )
    {
        $q = $this->_name_quotes[0];
        return $q . $s . $q;
    }





    /****************** SQL ***************************/





    /**
     * SPOC: Generate a 'CAST()'
     *
     * @param    string  An expression to typecast
     * @param    string  Type to typecast to
     * @param    integer Length
     * @return   string
     */
    public function sqlCAST( $expression, $type, $length = null )
    {
        $tmp = 'CAST('.$expression.' AS ';
        $type = $this->_typeCastMapping[$type];
        $result = $tmp . ' ' . $type . ')';
        return $result;
    }


    /**
     * SPOC: Generate a 'CASE WHEN..ELSE..END' SQL block
     *
     * @param    array   Array of conditions (WHENs)
     * @param    array   Array of results (THENs)
     * @param    string  (optional) default result (ELSE)
     * @param    string  (optional) Result data type to cast to
     * @return   string
     */
    public function sqlCASE( $conditions, $results, $defresult = null, $restype = '' )
    {
        // Validate sizes
        if ( (count($conditions) != count($results)) or (count($conditions) <= 0) ) {
            return '';
        }

        // Array of 'WHEN ... THEN ...';
        $when_array = array();

        $i = 0;
        foreach ( $conditions as $condition ) {
            $condition = trim( $condition );
            $result = trim( $results[$i] );
            if ( $restype != '' ) {
                $result = $this->sqlCAST( $result, $restype );
            }
            $when_array[] = 'WHEN ('.$condition.') THEN '.$result;
            $i++;
        }
        // Generate a 'WHEN.. THEN.. WHEN.. THEN..' string
        $code = implode( ' ', $when_array );

        // Check if we need 'ELSE' clause
        if ( ! empty($defresult) ) {
            if ( $restype != '' ) {
                $defresult = $this->sqlCAST( $defresult, $restype );
            }
            $code = $code.' ELSE '.$defresult;
        }

        // Final code
        $code = 'CASE '.$code.' END';

        return $code;
    }


    /**
     * SPOC: Generate a SUB-select item
     *
     * @param    string  The Sub-Select
     * @return   string
     *
     */
    public function sqlSUBSELECT( $select, $subselectname = null )
    {
        if ( ! empty($subselectname) ) {
            $subselname = $subselectname;
        } else {
            $subselname = $this->_subselectname;
        }
        $result = "(".$select.") AS $subselname";
        return $result;
    }


    /**
     * SPOC: Generate a MAX(expression)
     *
     * @param    string The expression
     * @return   string
     *
     */
    public function sqlMAX( $expression )
    {
        $result = "";
        if ( (!is_string($expression)) || empty($expression) ) {
            return $result;
        }
        $result = "MAX(" . $expression . ")";
        return $result;
    }



    /**
     * SPOC: Generate "expression IN (list)"
     *
     * @param   string  The expression
     * @param   array   Set of values to test
     * @param   boolean Emulate IN() with "OR...OR...OR"
     * @param   boolean To Quote or Not to quote values?
     * @return  string
     *
     */
    public function sqlIN( $expression, $list, $emulate = false, $quote = true )
    {
        $result = "";

        // Return empty string if no valid expression (string) or array is passed
        if ( (!is_string($expression)) || empty($expression) ) {
            return $result;
        }
        if ( (!is_array($list)) || (count($list) <= 0) ) {
            return $result;
        }

        $tmp = array();
        $tmp_emulated = array();
        foreach ($list as $element) {
            if ( !empty($element) ) {
                $tmp[] = $quote ? $this->Quote($element) : $element;
                $tmp_emulated[] = "(" . $expression . "=" . ($quote ? $this->Quote($element) : $element ) . ")";
            }
        }
        if ($emulate) {
            $result = "(" . implode(" OR ", $tmp_emulated) . ")";
        }
        else {
            $result = $expression . " IN (" . implode(', ', $tmp)  . ")";
        }
        return $result;
    }


    /**
     * Return final SQL query, the result of the whole job done here
     *
     * @return  string
     */
    public function getSQL()
    {
        $result = $this->_toString();
        return $result;
    }

    
    /**
     * Return TEXT quoting characters information
     *
     * @return  array 
     */
    public function getTextQuotes()
    {
        return $this->_text_quotes;
    }

    /**
     * Return NAME quoting characters information
     *
     * @return  array 
     */
    public function getNameQuotes()
    {
        return $this->_name_quotes;
    }
    
    
} // class


?>
