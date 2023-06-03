<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
// Types of Assetions Available https://phpunit.readthedocs.io/en/9.5/assertions.html
class SampleTest extends TestCase
{
	public function setUp():void
	{
		// this function is executed before each test function
		//var_dump( "HIHIHI");
		// you may initiate a variable so that it can be used in test methods
	}
	// assertion asserts that something matching what you expect to do
    public function testTrueAssertsToTrue()
	{
		$this->assertTrue(true);
	}
	// assertion asserts that something matching what you expect to do
	/** @test */
    public function CliWorks()
	{
		$coreParentInst = \OsolMVC\Core\CoreParent::getInstance();
		$this->assertEquals($coreParentInst->isCLI(), true);
		$this->assertTrue($coreParentInst->isCLI());
	}
	
	 public function testIf_DB_Class_Exists()
	 {
		$this->assertTrue(class_exists("\OSOLUtils\Helpers\OSOLMySQL"));
	 }//public function testIfDB_classExists()
	 
	/* 
	public function testIf_DB_is_Returned()
	{
		$coreParentInst = \OsolMVC\Core\CoreParent::getInstance();
		$emailHelperInst = \OsolMVC\Core\Helper\EmailHelper::getInstance();
		$this->assertEquals(
            $coreParentInst->getDB(),
            $emailHelperInst->getDB()
        );
	}
	 */
}