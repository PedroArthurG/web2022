<?php
final class Transaction
{
  private static $conexao;
  private function __construct()
  {
  }
  public static function open()
  {
    if (empty(self::$conexao)) {
      self::$conexao = Connection::open();
      self::$conexao->beginTransaction();
    }
  }
  public static function get()
  {
    return self::$conexao;
  }
  public static function close()
  {
    if (!empty(self::$conexao)) {
      self::$conexao->commit();
      self::$conexao = null;
    }
  }
  public static function rollback()
  {
    if (!empty(self::$conexao)) {
      self::$conexao->rollback();
      self::$conexao = null;
    }
  }
}