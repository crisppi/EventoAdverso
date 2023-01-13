<?php

class evento
{
  public $id_evento;
  public $data_evento;
  public $data_visita;
  public $paciente;
  public $idade;
  public $sexo;
  public $prolongamento;
  public $impacto;
  public $alta;
  public $obito;
  public $hospital;
  public $rel_evento;
  public $rel_prolongamento;
  public $rel_impacto;
  public $classificacao;
  public $senha;
  public $evitavel;
  public $auditor;
  public $gravidade;
  public $tipo_evento;
  public $empresa;
  public $propria;
  public $seguradora;
  public $status;
  public $ativo;
  public $negociado;
}

interface eventoDAOInterface
{

  public function buildevento($evento);
  public function findAll();
  public function findById($id_evento);
  public function findBypaciente($paciente);
  public function create(evento $evento);
  public function update(evento $evento);
  public function destroy($id_evento);
  public function findGeral();
};
