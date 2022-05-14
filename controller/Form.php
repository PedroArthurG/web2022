<?php
class Form
{
  private $message = "";
  private $error = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id", "");
    $form->set("equipe", "");
    $form->set("piloto", "");
    $form->set("posicao", "");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST["equipe"]) && isset($_POST["piloto"]) && isset($_POST["posicao"])) {
      try {
        $conexao = Transaction::get();
        $corrida = new Crud("corrida");
        $equipe = $conexao->quote($_POST["equipe"]);
        $config = $conexao->quote($_POST["piloto"]);
        $posicao = $conexao->quote($_POST["posicao"]);
        if (empty($_POST["id"])) {
          $corrida->insert(
            "equipe, piloto, posicao",
            "$equipe, $config, $posicao"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $corrida->update(
            "equipe = $equipe, piloto = $config, posicao = $posicao",
            "id = $id"
          );
        }
        $this->message = $corrida->getMessage();
        $this->error = $corrida->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    } else {
      $this->message = "Campos não informados!";
      $this->error = true;
    }
  }
  public function editar()
  {
    if (isset($_GET["id"])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $corrida = new Crud("corrida");
        $resultado = $corrida->select("*", "id = $id");
        if (!$corrida->getError()) {
          $form = new Template("view/form.html");
          foreach ($resultado[0] as $cod => $posicao) {
            $form->set($cod, $posicao);
          }
          $this->message = $form->saida();
        } else {
          $this->message = $corrida->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}
