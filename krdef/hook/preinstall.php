<?php
/* Execute install sql. */
$dbFile = $this->app->getAppRoot() . 'package' . DS . 'ext' . DS . 'krdef' . DS . 'db' . DS . 'krdefinstall.sql';
$sqls   = explode(';', file_get_contents($dbFile));
foreach($sqls as $sql)
{
    $sql = trim($sql);
    if(empty($sql)) continue;

    if(strpos($sql, 'DROP') !== false and $this->post->clearDB != false)
    {
        $sql = str_replace('--', '', $sql);
    }
    $sql = preg_replace('/`(\w+)_/', $this->config->db->name . ".`\${1}_" . $this->config->db->prefix, $sql);

    $this->dbh->query($sql);
}
