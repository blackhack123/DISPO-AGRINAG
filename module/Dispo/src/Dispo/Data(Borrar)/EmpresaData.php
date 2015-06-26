<?php

namespace General\Data;

/**
* EmpresaData.
*
*/
class EmpresaData
{
    /**
     * @var int
     */
    private $id;

	/**
     * @var string
	*/
    private $ruc;

	/**
     * @var string
	*/
    private $nombre_corto;
	
	/**
     * @var string
	*/
    private $nombre_completo;
    
    /**
     * @var string
     */
    private $siglas;

	/**
     * @var string
	*/
    private $direccion;

	/**
     * @var string
	*/
    private $telefonos;

	/**
     * @var string
	*/
    private $retencion_serie1;

	/**
     * @var string
	*/
    private $retencion_serie2;

	/**
     * @var string
	*/
    private $nro_retencion;

	/**
     * @var int
	*/
    private $pais_id;

	/**
     * @var int
	*/
	private $grupo_empresarial_id;	

		/**
     * @var int
	*/
	private $tipo_empleador_iess_id;	

	
	/**
	 * @var string
	 */
	private $objeto_social;
	

	/**
	 * @var string
	 */
	private $expediente_sc;
	
	
	/**
     * @var float
     */
   private $capital_autorizado;
	
   
   /**
    * @var string
    */
   private $nota_tabla_accionista;
    
   
   
   
   /**
    * @var string
    */
   private $gobierno_compania;
   
   
	/**
     * @var string
	*/
    private $estado;

	/**
     * @var string
	*/
    private $fecha_ing;

	/**
     * @var string
	*/
    private $fecha_mod;
	
	/**
     * @var int
	*/
    private $usuario_ing_id;

	/**
     * @var int
	*/
    private $usuario_mod_id;
	


	/*------------------------------------------------------------------------------*/
	/*------------------------------- METODOS GET y SET ----------------------------*/
	/*------------------------------------------------------------------------------*/

	//Metodos GET
	public function getId()					{return $this->id;}
	public function getRuc()				{return $this->ruc;}
	public function getNombreCorto()		{return $this->nombre_corto;}
	public function getNombreCompleto()		{return $this->nombre_completo;}
	public function getSiglas()		        {return $this->siglas;}
	public function getDireccion()			{return $this->direccion;}
	public function getTelefonos()			{return $this->telefonos;}
	public function getRetencionSerie1()	{return $this->retencion_serie1;}
	public function getRetencionSerie2()	{return $this->retencion_serie2;}
	public function getNroRetencion()		{return $this->nro_retencion;}
	public function getPaisId()				{return $this->pais_id;}
	public function getGrupoEmpresarialId()	{return $this->grupo_empresarial_id;}
	public function getTipoEmpleadorIessId(){return $this->tipo_empleador_iess_id;}
	
    public function getObjetoSocial()       {return $this->objeto_social;}
	public function getExpedienteSc()       {return $this->expediente_sc;}
	public function getCapitalAutorizado()  {return $this->capital_autorizado;}
	public function getNotaTablaAccionista(){return $this->nota_tabla_accionista;}
	public function getGobiernoCompania()   {return $this->gobierno_compania;}
	
	public function getEstado() 			{return $this->estado;}
	public function getFechaIng()			{return $this->fecha_ing;}
	public function getFechaMod() 			{return $this->fecha_mod;}
	public function getUsuarioIngId()		{return $this->usuario_ing_id;}
	public function getUsuarioModId()		{return $this->usuario_mod_id;}

	
	
	
	//Metodos SET
	public function setId($valor)					{$this->id 				= $valor;}
	public function setRuc($valor)					{$this->ruc				= $valor;}
	public function setNombreCorto($valor)			{$this->nombre_corto	= $valor;}
	public function setNombreCompleto($valor)		{$this->nombre_completo = $valor;}
	public function setSiglas($valor)			    {$this->siglas	        = $valor;}
	public function setDireccion($valor)			{$this->direccion		= $valor;}
	public function setTelefonos($valor)			{$this->telefonos		= $valor;}
	public function setRetencionSerie1($valor)		{$this->retencion_serie1= $valor;}
	public function setRetencionSerie2($valor)		{$this->retencion_serie2= $valor;}
	public function setNroRetencion($valor)			{$this->nro_retencion	= $valor;}
	public function setPaisId($valor)				{$this->pais_id			= $valor;}
	public function setGrupoEmpresarialId($valor)	{$this->grupo_empresarial_id = $valor;}	
	public function setTipoEmpleadorIessId($valor)	{$this->tipo_empleador_iess_id	= $valor;}	
	public function setEstado($valor)				{$this->estado 			= $valor;}	
	
	public function setObjetoSocial($valor)         {$this->objeto_social= $valor;}
	public function setExpedienteSc($valor)         {$this->expediente_sc= $valor;}
	public function setCapitalAutorizado($valor)    {$this->capital_autorizado= $valor;}
	public function setNotaTablaAccionista($valor)  {$this->nota_tabla_accionista= $valor;}
	public function setGobiernoCompania($valor)     {$this->gobierno_compania= $valor;}	
	
	
	public function setFechaIng($valor)				{$this->fecha_ing 		= $valor;}	
	public function setFechaMod($valor)				{$this->fecha_mod 		= $valor;}	
	public function setUsuarioIngId($valor)			{$this->usuario_ing_id 	= $valor;}	
	public function setUsuarioModId($valor)			{$this->usuario_mod_id 	= $valor;}		
	
}//end class	