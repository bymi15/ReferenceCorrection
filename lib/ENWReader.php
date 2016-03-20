<?php

class ENWReader {
  const EOL = "\r\n";
  const LINE_REGEX = '/^%([A-NP-Z0-9\?\@\!\#\$\]\&\(\)\*\+\^\>\<\[\=\~])\s+(.*)$/';
  protected $data = NULL;
  public function __construct($options = array()) {
  }
  /**
   * Parse an ENW file.
   *
   * This will parse the file and return a data structure representing the
   * record.
   *
   */
  public function parseFile($filename, $context = NULL) {
    if (!is_file($filename)) {
      throw new Exception(sprintf('File %s not found.', htmlentities($filename)));
    }
    $flags = FILE_SKIP_EMPTY_LINES | FILE_TEXT;
    $contents = file($filename, $flags, $context);
    $this->parseArray($contents);
  }
  /**
   * Parse a string of ENW data.
   *
   * This will parse an ENW record into a representative data structure.
   *
   */
  public function parseString($string) {
    $contents = explode (ENWReader::EOL, $string);
    $this->parseArray($contents);
  }
  /**
   * Take an array of lines and parse them into an ENW record.
   */

  protected function parseArray($lines) {
    $recordset = array();
    // Do any cleaning and normalizing.
    $this->cleanData($lines);
    $record = array();
    $lastTag = NULL;

    foreach ($lines as $line) {
      $line = trim($line);
      $matches = array();
      preg_match(self::LINE_REGEX, $line, $matches);

      if (!empty($matches[1])) {
        $lastTag = $matches[1];
        $record[$matches[1]][] = trim($matches[2]);
      }
      elseif (!empty($matches[2])) {
        // Append to the last one.
        if (!empty($lastTag)) {
          $lastEntry = count($record[$lastTag]) - 1;
          // Trim because some encoders add tabs or multiple spaces.
          $record[$lastTag][$lastEntry] .= ' ' . trim($matches[2]);
        }
      }
      // End record and prep a new one.
      elseif (empty($matches) && !empty($lastTag)) {
        $lastTag = NULL;
        $recordset[] = $record;
        $record = array();
      }
    }

    if (!empty($record)) $recordset[] = $record;

    $reference_list = array();

    for($i = 0; $i < count($recordset); $i++){
      $authors = $recordset[$i]['A'];
      $title = $recordset[$i]['T'][0];
      $journal = $recordset[$i]['J'][0];
      $pages = $recordset[$i]['P'][0];
      $vol = $recordset[$i]['V'][0];
      $year = $recordset[$i]['D'][0];

      $current_reference = (string)($i+1) . '. ';

      for($j = 0; $j < count($authors); $j++){
        if($j==count($authors)-1){
          $current_reference .= (string)$authors[$j] . ' ';
        }else{
          $current_reference .= (string)$authors[$j] . ', ';
        }
      }

      if(!empty($pages)){
        $current_reference .= $title . '. ' . $journal . ' ' . $year . ';' . $vol . ':' . $pages;
      }else{
        $current_reference .= '(' . $year . '). ' . $title . '. ' . $journal . ' ' . $vol . '.';
      }

      $reference_list[$i] = $current_reference;
    }

    $this->data = $reference_list;
  }

  public function getRecords() {
    return $this->data;
  }

  /**
   * Clean up the data before processing.
   */
  protected function cleanData(&$lines) {
    if (empty($lines)) return;
    $first = $lines[0];
    if (substr($first, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf)) {
      $lines[0] = substr($first, 3);
    }
  }
}
