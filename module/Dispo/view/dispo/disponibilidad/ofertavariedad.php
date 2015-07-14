	<div class="table-container">  
  		
          <table class="table-dispo border">
              <tr>
                  <td class="th-inner"  width="90">Precio Normal</td>
                  <td class="th-inner"  width="90">Precio Oferta</td>
                  <td class="th-inner"  width="70">Grado</td>
                  <td class="th-inner"  width="80">type box</td>
                  <td class="th-inner"  width="60">Cantidad</td>
                  <td class="th-inner"  width="90">Order</td>                               
              </tr>
         
              <tr>
                  <td width="90">
                     {{ctrl.precio_normal}}                             
                  </td>                                
                  <td width="90">
                      <b><?php echo($this->reg_grupo_precio_det['precio_oferta']); ?></b>                               
                  </td>
                  <td width="70">
                      <?php echo($this->reg_grupo_precio_det['grado_id']); ?>                           
                  </td>                            
                  <td width="80">
                      <select class="form-control select-class" ng-model="ctrl.item_oferta.tob_oferta"
                         ng-change="ctrl.cantidad_cajas_oferta(ctrl.item_oferta.tob_oferta,ctrl.item_oferta.variedad_id,ctrl.item_oferta.grado_id)"
                         ng-options="caja for caja in ctrl.variedad_oferta_cajas">
                      </select>
                  </td>
                  <td width="60">
                       {{ctrl.numero_cajas_oferta}}
                  </td>
                  <td width="90">
                       <select class="form-control select-class" ng-model="ctrl.item_oferta.listindex" ng-options="ind for ind in ctrl.listaHB(ctrl.numero_cajas_oferta)" ng-init="ctrl.item_oferta.listindex=0"></select>
                  </td>
              </tr>
          </table>
  	</div>  

  	<div class="space-around">
      	Variedades para Seleccionar
  	</div>

  	<div class="table-container">  
          <table class="table-dispo border">
              <tr>
                  <td class="th-inner"  width="200">Variedad</td>
                  <td class="th-inner"  width="70">Grado</td>
                  <td class="th-inner"  width="70">Precio</td>
                  <td class="th-inner"  width="90">type box</td>
                  <td class="th-inner"  width="90">Cantidad</td>                               
                  <td class="th-inner" width="150">order</th>
              </tr>
         
              <tr ng-repeat="oferta in ctrl.oferta | orderBy:'nombre'">                                
                  <td width="200">
                      {{oferta.variedad_hueso_nombre}}                                
                  </td>
                  <td width="70">
                      {{oferta.grado_combo_id}}                                        
                  </td>
                   <td width="70">
                      {{oferta.precio}}                                        
                  </td>                            
                  <td width="90">
                      {{ctrl.item_oferta.tob_oferta}}
                  </td>
                  <td width="90">
                      {{ctrl.get_cantidad_x_factor(oferta.factor_combo,ctrl.item_oferta.listindex)}}
                  </td>
                  <td width="150">
                      <input type="radio" class="form-control"
                        ng-model="ctrl.hueso_escogido"
                        ng-value="$index"
                        id="{{oferta.variedad_hueso_nombre}}"
                        name="hueso_escogido"
                       />

                      <!-- <input type="checkbox" value="{{items.chkselect}}" ng-model="items.chkselect" 
                        ng-true-value="1" ng-false-value="0"
                        ng-click="ctrl.toggleSelect(items.pedido_det_sec,items.chkselect)" 
                        ng-if="ctrl.preguntachk(items.marcacion)"> -->
                     <!-- <div class="btn btn-xs btn-success" ng-click="ctrl.add($index,oferta,marcacion,ag_carga)" ng-disabled="ctrl.disable_add">
                          <span class="glyphicon glyphicon-plus-sign"></span> Add
                      </div> -->
                  </td>
              </tr>
          </table>
  	</div>

  	{{ctrl.hueso_escogido}}

            
	<div class="btn btn-xs btn-success pull-right space-around" ng-click="ctrl.add_oferta(ctrl.oferta,ctrl.hueso_escogido,marcacion,ag_carga)" ng-disabled="ctrl.show_aplicar_oferta(ctrl.hueso_escogido)">
		<span class="glyphicon glyphicon-plus-sign"></span> Aplicar OFERTA
	</div>                 
