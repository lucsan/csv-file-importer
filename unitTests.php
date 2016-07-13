<?php
/**
 * File to test claro/main.php
 * Requires main.php
 */

namespace claro\ut;

include_once('main.php');

$ut = new unitTests;

/**
 * UnitTests class. Automatically runs unitTests for main.php
 */
class unitTests {
  private static $main = null;
  private $input = 'input.csv';
  private $output = 'output.csv';

  /**
   * Create main object and run each test_ method in class.
   */
  public function __construct ()
  {
    self::$main = new \claro\main();

    // Tests Autolodaer.
    $allFuncs = get_class_methods($this);
    foreach ($allFuncs as $func) {
      if (strpos($func, 'test_') === 0) {
        $this->$func();
      }
    }
  }

  /**
   * Test the import file exists.
   */
  public function test_inputFileExists ()
  {
    if (file_exists($this->input)) unlink($this->input);
    assert("!file_exists('{$this->input}') /* Input file should not exist. */");
    $this->makeFileStub();
    assert("file_exists('{$this->input}') /* Input file should exist. */");
  }

  /**
   * Test main object exits.
   */
  public function test_mainObjectExists ()
  {
    assert(is_object(self::$main), '/* Main object should exist. */');
  }

  /**
   * Test main functions for importing data.
   */
  public function test_importData ()
  {
    if (file_exists($this->input)) unlink($this->input);
    $this->makeFileStub();
    assert("file_exists('{$this->input}') /* Input file should exist. */");
    $returned = self::$main->importData();
    assert($returned[1][1] == 42.20, 'imported[1][1] should be 42.20');
  }

  /**
   * Test main function for processing data.
   */
  public function test_processData ()
  {
    $returned = self::$main->processData();
    assert($returned['Hotel']['amount'] == 4, "/* processed['Hotel', ['amount'] should be 4. */");
    assert(file_exists($this->output), 'output.csv file should exist.');
    $outputFile = file_get_contents($this->output);
    assert(stripos($outputFile, 'Fuel') == 11, 'Fuel should start at 11th char.');
  }

  /**
   * UT helper function, creates data stub and import.csv for testing.
   * Note: if you adjust the data you need to compensate in the test_importData and
   * test_processData assertions.
   */
  private function makeFileStub () {
    $data = [];
    $data[] = ['Hotel', 75.00, 2];
    $data[] = ['Fuel', 42.20, 1];
    $data[] = ['Food', 15.78, 1];
    $data[] = ['Hotel', 69.00, 2];
    foreach ($data as $item) {
      $csv = implode(',', $item);
      $csv .= PHP_EOL;
      file_put_contents($this->input, $csv, FILE_APPEND);
    }
  }
}

?>
