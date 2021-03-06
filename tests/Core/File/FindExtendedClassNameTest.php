<?php
/**
 * Tests for the PHP_CodeSniffer_File:findExtendedClassName method.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Tests for the PHP_CodeSniffer_File:findExtendedClassName method.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Core_File_FindExtendedClassNameTest extends PHPUnit_Framework_TestCase
{

    /**
     * The PHP_CodeSniffer_File object containing parsed contents of this file.
     *
     * @var PHP_CodeSniffer_File
     */
    private $_phpcsFile;


    /**
     * Initialize & tokenize PHP_CodeSniffer_File with code from this file.
     *
     * Methods used for these tests can be found at the bottom of
     * this file.
     *
     * @return void
     */
    public function setUp()
    {
        $phpcs            = new PHP_CodeSniffer();
        $this->_phpcsFile = new PHP_CodeSniffer_File(
            __FILE__,
            array(),
            array(),
            $phpcs
        );

        $contents = file_get_contents(__FILE__);
        $this->_phpcsFile->start($contents);

    }//end setUp()


    /**
     * Clean up after finished test.
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->_phpcsFile);

    }//end tearDown()


    /**
     * Test a class that extends another.
     *
     * @return void
     */
    public function testExtendedClass()
    {
        $start = ($this->_phpcsFile->numTokens - 1);
        $class = $this->_phpcsFile->findPrevious(
            T_COMMENT,
            $start,
            null,
            false,
            '/* testExtendedClass */'
        );

        $found = $this->_phpcsFile->findExtendedClassName(($class + 2));
        $this->assertSame('testFECNClass', $found);

    }//end testExtendedClass()


    /**
     * Test a class that extends another, using namespaces.
     *
     * @return void
     */
    public function testNamespacedClass()
    {
        $start = ($this->_phpcsFile->numTokens - 1);
        $class = $this->_phpcsFile->findPrevious(
            T_COMMENT,
            $start,
            null,
            false,
            '/* testNamespacedClass */'
        );

        $found = $this->_phpcsFile->findExtendedClassName(($class + 2));
        $this->assertSame('\testFECNClass', $found);

    }//end testNamespacedClass()


    /**
     * Test a class that doesn't extend another.
     *
     * @return void
     */
    public function testNonExtendedClass()
    {
        $start = ($this->_phpcsFile->numTokens - 1);
        $class = $this->_phpcsFile->findPrevious(
            T_COMMENT,
            $start,
            null,
            false,
            '/* testNonExtendedClass */'
        );

        $found = $this->_phpcsFile->findExtendedClassName(($class + 2));
        $this->assertFalse($found);

    }//end testNonExtendedClass()


    /**
     * Test an interface.
     *
     * @return void
     */
    public function testInterface()
    {
        $start = ($this->_phpcsFile->numTokens - 1);
        $class = $this->_phpcsFile->findPrevious(
            T_COMMENT,
            $start,
            null,
            false,
            '/* testInterface */'
        );

        $found = $this->_phpcsFile->findExtendedClassName(($class + 2));
        $this->assertFalse($found);

    }//end testInterface()


}//end class

// @codingStandardsIgnoreStart
class testFECNClass {}
/* testExtendedClass */ class testFECNExtendedClass extends testFECNClass {}
/* testNamespacedClass */ class testFECNNamespacedClass extends \testFECNClass {}
/* testNonExtendedClass */ class testFECNNonExtendedClass {}
/* testInterface */ interface testFECNInterface {}
// @codingStandardsIgnoreEnd
