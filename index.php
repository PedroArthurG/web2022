<?php
// auto load
spl_autoload_extensions('.php');
function classLoader($class)
{
  $pastas = array('controller', 'model');
  foreach ($pastas as $pasta) {
    $arquivo = "{$pasta}/{$class}.php";
    if (file_exists($arquivo)) {
      require_once($arquivo);
    }
  }
}
spl_autoload_register("classLoader");
// Front Controller
class Aplicacao
{
  static private $app = "/web2022";
  public static function run()
  {
    $layout = new Template('view/layout.html');
    $method = "";
    if (isset($_GET["class"])) {
      $class = $_GET["class"];
    }
    if (isset($_GET["method"])) {
      $method = $_GET["method"];
    }
    if (empty($class)) {
      $class = "Inicio";
    }
    if (class_exists($class)) {
      $pagina = new $class();
      if (method_exists($pagina, $method)) {
        $pagina->$method();
      } else {
        $pagina->controller();
      }
      $layout->set('uri', self::$app);
      $layout->set('conteudo', $pagina->getMessage());
    }
    echo $layout->saida();
  }
}
Aplicacao::run();
