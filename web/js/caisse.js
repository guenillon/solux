$(document).ready(function() {
	// Déclenche le script uniquement sur la vue caisse
	if($("#caisse-config").length == 1) {
		var lDataTable = $('.jpi_table_data_table_caisse').DataTable({
		    "language": {
		    	"url": "https://cdn.datatables.net/plug-ins/3cfcc339e89/i18n/French.json"
		    },
		    "columnDefs": [
		         { "visible": false, "targets": [0, 1] },
		         { "orderable": false, "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] },
		         { "className": "text-right", "targets": [ 3, 4, 6, 8, 9 ] }
	         ],
	         "order": [[ 1, 'asc' ], [ 2, 'asc' ]],
	         "drawCallback": function ( settings ) {
	             var api = this.api();
	             var rows = api.rows( {page:'current'} ).nodes();
	             var last=null;
	             api.column(1, {page:'current'} ).data().each( function ( group, i ) {
	                 if ( last !== group ) {
	                     $(rows).eq( i ).before(
	                         '<tr class="group"><td colspan="9">'+group+'</td></tr>'
	                     );
	  
	                     last = group;
	                 }
	             } );
	         }
		});
			
		var lock = false;
		$('[name=jpi_soluxbundle_recherche_produit]').keyup(function () {
			var lCodeBarre = $(this).find("#jpi_soluxbundle_recherche_produit_codeBarre").val();
			if(lCodeBarre.length == 13 && !lock) {
				submitSearchProduit($(this));
			}
		}).submit(function() {
			if(!lock) {
				submitSearchProduit($(this));
			}
			return false;
		});
		
		function submitSearchProduit(form) {
			lock = true;
			$(form).ajaxSubmit({"clearForm":true, "success": addProduit, "dataType": 'json'});
		}
		
		function loadProduit() {
			var lId = [];
			$( "select[name$='[produit]']" ).each(function() {
				lId.push($(this).val());
			});
	
			if(lId.length > 0) {
				var lPath = "./produits";
				$.post(lPath, {'id': lId}, function( data ) {
					loadAddProduit(data);		
				}, "json");
			}
		}
		
		function reloadForm() {
			
			$( "select[name$='[produit]']" ).each(function() {
				var lIdProduit = $(this).val();
				
				var lIdAttr = $(this).attr('id');
				var lFormName = 'jpi_soluxbundle_achat_detail_';
				var lStart = parseInt(lFormName.length);
				var lLength = parseInt(lIdAttr.lastIndexOf("_")) - lStart;
				var lFalseId = lIdAttr.substr(lStart,lLength);				
				var ListeAttribut = ['produit', 'quantite', 'unite', 'prix', 'prixPaye', 'taux'];

				var lSelector = '#' + lFormName + lFalseId + '_';
				var lNewId = lFormName + lIdProduit + '_';
				var lNewName = 'jpi_soluxbundle_achat[detail][' + lIdProduit + ']['; 
				$.each(ListeAttribut, function() {
					$(lSelector + this).attr('name', lNewName + this + ']').attr('id', lNewId  + this);
				});
				
				var $prototype = $(this).parent().parent().parent().parent().parent();
				// Suppression de la ligne
				$("#delete-row-" + lIdProduit).click(function(e) { 					
			  		$prototype.remove();
			  		lDataTable.row( $(this).parents('tr') ).remove().draw();
			  		majTotal();
			  		e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				// Ajout de quantité
				$("#add-qte-" + lIdProduit).click(function(e) { 
					majQuantite($(this), true);
					e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				// Ajout de quantité
				$("#delete-qte-" + lIdProduit).click(function(e) { 			
					majQuantite($(this), false);
					e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
			});
			//majTotal();
		}
		
		if($("#caisse-config").data("add") == "1") {
			loadProduit();
		} else {
			reloadForm();
			$(".hide-show").hide();
			$("#btn-edit").click(function(e) {
				$(".btn-group-edit").hide();
				$(".hide-show").show();
				e.preventDefault(); // évite qu'un # apparaisse dans l'URL
			});
		}
		
		function loadAddProduit(produits) {
			var lTaux = parseFloat($('#taux').data("taux"));
			
			$(produits).each(function() {
				var produit = this;			
				var lEnteteNomChamp = '#jpi_soluxbundle_achat_detail_' + produit.id;

				var lQuantite = parseFloat($(lEnteteNomChamp + '_quantite').val().replace(',','.'));
				var lPrix = (lQuantite * parseFloat(produit.prix)).toFixed(2);
				var lPrixPaye = produit.prix;
				if(!produit.prix_fixe) {
					lPrixPaye = (parseFloat(lPrix)*lTaux).toFixed(2);
				}
				
				var lQuantiteMax = -1;
				var lQuantiteAchatTotal = -1;
				
				var ldataProduit =
					'data-produit-id="' + produit.id + '" ' +
                    'data-produit-prix="' + produit.prix + '" ' +
                    'data-produit-prix_fixe="' + produit.prix_fixe + '" ' +
                    'data-produit-unite="' + produit.unite + '" ' +
                    'data-produit-quantite="' + produit.quantite + '" ' +
                    'data-produit-limite-quantite_max="' + lQuantiteMax + '" ' +
                    'data-produit-quantite-achat-total="' + lQuantiteAchatTotal + '" ';
				
				var lNode = lDataTable.row.add( [ produit.id,
							                      produit.categorie.nom,
							                      produit.nom,
							                      produit.quantite + ' ' + produit.unite,
							                      $.number(produit.prix, 2, ',', ' ' ) + ' €',
							                      '<a href="#" id="delete-qte-' + produit.id + '" class="btn btn-xs btn-block btn-default delete-qte" ' + ldataProduit + '><span class="glyphicon glyphicon-minus-sign"></span></a>',
							                      $.number(lQuantite, 2, ',', ' ' ) + ' ' + produit.unite,
							                      '<a href="#" id="add-qte-' + produit.id + '" class="btn btn-xs btn-block btn-default add-qte" ' + ldataProduit + '><span class="glyphicon glyphicon-plus-sign"></span></a>',
							                      $.number(lPrix, 2, ',', ' ' ) + ' €',
							                      $.number(lPrixPaye, 2, ',', ' ' ) + ' €',
							                      '<a href="#" id="delete-row-' + produit.id + '" class="btn btn-xs btn-block btn-default delete-row"><span class="glyphicon glyphicon-remove"></span></a>'
							                 ] ).draw().node();
				
				var $prototype = $(lEnteteNomChamp).parent().parent();
				// Suppression de la ligne
				$(lNode).find(".delete-row").click(function(e) { 					
			  		$prototype.remove();
			  		lDataTable.row( $(this).parents('tr') ).remove().draw();
			  		majTotal();
			  		e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				// Ajout de quantité
				$(lNode).find(".add-qte").click(function(e) { 			
					majQuantite($(this), true);
					e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				// Ajout de quantité
				$(lNode).find(".delete-qte").click(function(e) { 			
					majQuantite($(this), false);
					e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
			});
			
			majTotal();
		}
		
		function addProduit(response, statusText, xhr, $form)  { 
			lock = false;
			
			if(response.produit != "") { // Si la recherche retourne un produit
				var produit = response.produit;			
				var lEnteteNomChamp = '#jpi_soluxbundle_achat_detail_' + produit.id;
				var lTaux = parseFloat($('#taux').data("taux"));
				
				// Vérifier si le produit est déjà dans l'achat
				if($( lEnteteNomChamp + '_produit').length > 0) { // Maj de la quantité et du prix
					majQuantite($("#add-qte-" + produit.id), true);	
				} else { // Ajout du produit au formulaire
					var lQuantiteMax = -1;
					var lQuantiteAchatTotal = parseFloat(response.quantiteAchat.total);
					if(isNaN(lQuantiteAchatTotal)) {
						lQuantiteAchatTotal = 0;
					}
					// On a dépassé la limite max
					if( response.produit.limites[0]
						&& response.produit.limites[0].quantite_max != "" ) {
						lQuantiteMax = response.produit.limites[0].quantite_max;
						if(parseFloat(response.produit.limites[0].quantite_max) < (lQuantiteAchatTotal + parseFloat(produit.quantite)).toFixed(2)) {
							$("#quantite-max").modal('show'); 
						}						
					}
					
					var lPrixPaye = produit.prix;
					var lTauxProduit = 1;
					if(!produit.prix_fixe) {
						lPrixPaye = (parseFloat(produit.prix)*lTaux).toFixed(2);
						lTauxProduit = lTaux;
					}
					
					var $prototype = $($("#jpi_soluxbundle_achat_detail").attr('data-prototype')
							.replace(/__name__label__/g, produit.nom)
						    .replace(/__name__/g, produit.id))
					$prototype.find(lEnteteNomChamp + '_produit').val(produit.id);
					$prototype.find(lEnteteNomChamp + '_quantite').val(produit.quantite);
					$prototype.find(lEnteteNomChamp + '_unite').val(produit.unite);
					$prototype.find(lEnteteNomChamp + '_prix').val(produit.prix);
					$prototype.find(lEnteteNomChamp + '_prixPaye').val(lPrixPaye);
					$prototype.find(lEnteteNomChamp + '_taux').val(lTauxProduit);
	
					var ldataProduit =
						'data-produit-id="' + produit.id + '" ' +
	                    'data-produit-prix="' + produit.prix + '" ' +
	                    'data-produit-prix_fixe="' + produit.prix_fixe + '" ' +
	                    'data-produit-unite="' + produit.unite + '" ' +
	                    'data-produit-quantite="' + produit.quantite + '" ' +
	                    'data-produit-limite-quantite_max="' + lQuantiteMax + '" ' +
	                    'data-produit-quantite-achat-total="' + lQuantiteAchatTotal + '" ';
					
					var lNode = lDataTable.row.add( [ produit.id,
					                      produit.categorie.nom,
					                      produit.nom,
					                      produit.quantite + ' ' + produit.unite,
					                      $.number(produit.prix, 2, ',', ' ' ) + ' €',
					                      '<a href="#" id="delete-qte-' + produit.id + '" class="btn btn-xs btn-block btn-default delete-qte" ' + ldataProduit + '><span class="glyphicon glyphicon-minus-sign"></span></a>',
					                      $.number(produit.quantite, 2, ',', ' ' ) + ' ' + produit.unite,
					                      '<a href="#" id="add-qte-' + produit.id + '" class="btn btn-xs btn-block btn-default add-qte" ' + ldataProduit + '><span class="glyphicon glyphicon-plus-sign"></span></a>',
					                      $.number(produit.prix, 2, ',', ' ' ) + ' €',
					                      $.number(lPrixPaye, 2, ',', ' ' ) + ' €',
					                      '<a href="#" id="delete-row-' + produit.id + '" class="btn btn-xs btn-block btn-default delete-row"><span class="glyphicon glyphicon-remove"></span></a>'
					                 ] ).draw().node();
	
					// Suppression de la ligne
					$(lNode).find(".delete-row").click(function(e) { 					
				  		$prototype.remove();
				  		lDataTable.row( $(this).parents('tr') ).remove().draw();
				  		majTotal();
				  		e.preventDefault(); // évite qu'un # apparaisse dans l'URL
					    return false;
				  	});
					
					// Ajout de quantité
					$(lNode).find(".add-qte").click(function(e) { 			
						majQuantite($(this), true);
						e.preventDefault(); // évite qu'un # apparaisse dans l'URL
					    return false;
				  	});
					
					// Ajout de quantité
					$(lNode).find(".delete-qte").click(function(e) { 			
						majQuantite($(this), false);
						e.preventDefault(); // évite qu'un # apparaisse dans l'URL
					    return false;
				  	});
					
					$("#jpi_soluxbundle_achat_detail").after($prototype);
					majTotal();
				}		
			} else {
				$("#produit-inconnu").modal('show');
			}
		}
		
		function majQuantite(produit, lAjout) {
			var lEnteteNomChamp = '#jpi_soluxbundle_achat_detail_' + produit.data('produit-id');
			
			var lQuantiteActuelle = parseFloat($(lEnteteNomChamp + '_quantite').val().replace(',','.'));
			var lQuantiteDelta = parseFloat(produit.data('produit-quantite'));
			
			var lQuantite = 0;
			if(lAjout) {
				lQuantite = (lQuantiteActuelle + lQuantiteDelta).toFixed(2);
			} else {
				lQuantite = (lQuantiteActuelle - lQuantiteDelta).toFixed(2);
			}
			
			// Pas de Quantité négative
			if(lQuantite > 0) {
				// Si la nouvelle quantité dépasse la limite max
				if(produit.data('produit-limite-quantite_max') != -1
					&& produit.data('produit-quantite-achat-total') != -1					
					&& parseFloat(produit.data('produit-limite-quantite_max')) < (parseFloat(produit.data('produit-quantite-achat-total')) + parseFloat(lQuantite)).toFixed(2))
					 {
					$("#quantite-max").modal('show');
				} 
				var lPrix = (parseFloat(lQuantite) * parseFloat(produit.data('produit-prix'))).toFixed(2);

				var lPrixPaye = lPrix;
				if(!produit.data('produit-prix_fixe')) {
					lPrixPaye = ( lPrix * parseFloat($('#taux').data("taux") )).toFixed(2);
				}
			
				$(lEnteteNomChamp + '_quantite').val(lQuantite);
				$(lEnteteNomChamp + '_prix').val(lPrix);
				$(lEnteteNomChamp + '_prixPaye').val(lPrixPaye);
				
				var lLigne = 
					produit.parent().parent().children().each(function(index) {
						switch(index) {
							case 4:
								$(this).text($.number(lQuantite, 2, ',', ' ' ) + ' ' + produit.data('produit-unite'));
								break;
							case 6:
								$(this).text($.number(lPrix, 2, ',', ' ' )+ ' €');
								break;
							case 7:
								$(this).text($.number(lPrixPaye, 2, ',', ' ' )+ ' €');
								break;
						}
					});				
				majTotal();
			}
		}
			
		$("#btn-submit").hide();
		$("#versement").keyup(function() {majVersement();}).number( true, 2 );
		$("#jpi_soluxbundle_recherche_produit_codeBarre" ).focus();
		
		function majTotal() {
			var lTotal = calculTotalCol('prix');
			$("#total").text($.number(lTotal, 2, ',', ' ' ));		
			$("#jpi_soluxbundle_achat_montant").val(lTotal);
			
			var lTotalPaye = calculTotalCol('prixPaye'); 
			$("#total-paye").text($.number(lTotalPaye, 2, ',', ' ' ));	
			$("#jpi_soluxbundle_achat_montantPaye").val(lTotalPaye);
			majVersement();
			
			var lMontantMax = parseFloat($("#montantMax").data('montantmax'));
			
			if(lMontantMax != -1 && lTotal > lMontantMax) {
				$("#montant-max").modal('show');
			}
			
			if(lTotal == 0) {
				$("#btn-submit").hide();
			} else {
				$("#btn-submit").show();
			}		
		}
		
		function calculTotalCol(col) {
			
			var lTotal = 0;
			$( "input[name$='[" + col + "]']" ).each(function() {
				lTotal = (parseFloat(lTotal) + parseFloat($(this).val().replace(',','.'))).toFixed(2);			
			});
			return lTotal;
		}
		
		function majVersement() {
			var lVersement = parseFloat($("#versement").val().replace(',','.'));
			
			var lRendre = 0;
			if(!isNaN(lVersement)) {
				var lTotalPaye = calculTotalCol('prixPaye');
				lRendre = (lVersement - lTotalPaye).toFixed(2);
				if(lRendre < 0) {
					lRendre = 0;
				}
			}
			$("#rendre").html($.number(lRendre, 2, ',', ' ' ));
		}
	}
} );