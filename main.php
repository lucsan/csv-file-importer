<?php
/**
 * Main functions to open import.csv, calcualte values and create an array and
 * output.csv file.
 */
namespace claro;

class main {
  private $error = null;
  private $input = 'input.csv';
  private $output = 'output.csv';
  private $rawData = null;
  private $procData = null;

  /**
   * Imports data from import.csv.
   * @return Array Array of raw data extracted from import.csv.
   */
  public function importData ()
  {
    if (!file_exists($this->input)) return $this->error("Missing input file {$this->input}.");
    $string = file_get_contents($this->input);
    $inputs = explode(PHP_EOL, $string);
    $data = [];
    $countLine = 0;
    foreach ($inputs as $input) {
      // Ignore any blank lines.
      if ($input == '') continue;
      $item = explode(',', $input);
      // Do data integrity checks.
      if (count($item) < 3) return $this->error("Data Error: input line {$countLine}. ");
      $data[] = $item;
      $countLine++;
    }
    // This allows both internal use of the raw data and passes it back for UT.
    $this->rawData = $data;
    return $data;
  }

  /**
   * Takes raw data array and calculates item totals.
   * @return Array Array of processed results.
   */
  public function processData ()
  {
    $data = [];
    $str = '';
    foreach ($this->rawData as $values) {
      if (!isset($data[$values[0]])) {
        $data[$values[0]] = ['cost' => 0];
      }
      $price = $values[1] - 0;
      $amount = $values[2] - 0;
      $cost = $price * $amount;
      $data[$values[0]]['cost'] += $cost;
    }
    // Create output file.
    if (file_exists($this->output)) unlink($this->output);
    foreach ($data as $key => $value) {
      $str = "{$key},{$value['cost']}" . PHP_EOL;
      file_put_contents($this->output, $str, FILE_APPEND);
    }
    $this->procData = $data;
    print_r($data);
    return $data;
  }

  /**
   * Helper function, prints an error message and returns control to calling function.
   * @param  String $msg An error message.
   */
  public function error (String $msg)
  {
    print "Error: {$msg}";
  }
}

?>
