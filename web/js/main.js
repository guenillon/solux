$(document).ready(function() {	
	$('.jpi_table_data_table').DataTable({
	    "language": {
		"url": "https://cdn.datatables.net/plug-ins/3cfcc339e89/i18n/French.json"
	    }
	});

	$(".clickableRow").click(function() {
        window.document.location = $(this).data("href");
	});

	formulaireImbrique("jpi_soluxbundle_produit_limites", "Limite", "une Limite");
	formulaireImbrique("jpi_soluxbundle_famille_membres", "Membre", "un Membre");
	
	function formulaireImbrique($fieldName, $fieldLabel, $fieldAddLabel) {		
		//On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse.
		var $container = $('div#' + $fieldName);
		
		// On ajoute un lien pour ajouter une nouvelle catégorie
		var $addLink = $('<a href="#" id="add_category" class="btn btn-default">Ajouter '+$fieldAddLabel+'</a>');
		$container.append($addLink);
		
		// On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
		$addLink.click(function(e) {
		  addLimite($container, $fieldName);
		  e.preventDefault(); // évite qu'un # apparaisse dans l'URL
		  return false;
		});
		
		// On définit un compteur unique pour nommer les champs qu'on va ajouter dynamiquement
		var index = $container.find(':input').length;
		
		// On ajoute un premier champ automatiquement s'il n'en existe pas déjà un (cas d'une nouvelle annonce par exemple).
		if (index == 0) {
		 // addLimite($container);
		} else {
		  // Pour chaque catégorie déjà existante, on ajoute un lien de suppression
		  $container.children('div').each(function() {
		    addDeleteLink($(this), $fieldName);
		  });
		}
		
		// La fonction qui ajoute un formulaire Categorie
		function addLimite($container, $fieldName) {
		  // Dans le contenu de l'attribut « data-prototype », on remplace :
		  // - le texte "__name__label__" qu'il contient par le label du champ
		  // - le texte "__name__" qu'il contient par le numéro du champ
		  var $prototype = $($container.attr('data-prototype')
			.replace(/__name__label__/g, $fieldLabel+' n°' + (index+1))
		    .replace(/__name__/g, index));
		
		  // On ajoute au prototype un lien pour pouvoir supprimer la catégorie
		  addDeleteLink($prototype, $fieldName);
		
		  // On ajoute le prototype modifié à la fin de la balise <div>
		  $container.parent().parent().after($prototype);
		
		  // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
		  index++;
		}
		
		// La fonction qui ajoute un lien de suppression d'une catégorie
		function addDeleteLink($prototype, $fieldName) {
		  // Création du lien
		  $deleteLink = $('<a href="#" class="btn btn-danger pull-right">Supprimer</a>')
		   	// Ajout du listener sur le clic du lien
		  	.click(function(e) { 
		  		$prototype.remove();
		  		e.preventDefault(); // évite qu'un # apparaisse dans l'URL
			    return false;
		  	});
		
		  // Ajout du lien
		  $prototype.find('[id^=' + $fieldName + '_]').first().append($deleteLink);
		}
	
	}
	
	
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
	
	function addProduit(response, statusText, xhr, $form)  { 
		lock = false;
		
		if(response.produit != "") { // Si la recherche retourne un produit
			var produit = response.produit;			
			var lEnteteNomChamp = '#jpi_soluxbundle_achat_detail_' + produit.id;
			var lTaux = parseFloat($('#taux').data("taux"));
			
			// Vérifier si le produit est déjà dans l'achat
			if($( lEnteteNomChamp + '_produit').length > 0) { // Maj de la quantité et du prix
				majQuantite(lEnteteNomChamp, response, lTaux, true);	
			} else { // Ajout du produit au formulaire
				// On a dépassé la limite max
				if( response.produit.limites[0]
					&& response.produit.limites[0].quantite_max != "" 
					&& parseFloat(response.produit.limites[0].quantite_max) < (parseFloat(response.quantiteAchat.total) + parseFloat(produit.quantite)).toFixed(2)) {
					$("#quantite-max").modal('show');
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

				var lNode = lDataTable.row.add( [ produit.id,
				                      produit.categorie.nom,
				                      produit.nom,
				                      produit.quantite + ' ' + produit.unite,
				                      $.number(produit.prix, 2, ',', ' ' ) + ' €',
				                      '<a href="#" class="btn btn-xs btn-block btn-default delete-qte"><span class="glyphicon glyphicon-minus-sign"></span></a>',
				                      $.number(produit.quantite, 2, ',', ' ' ) + ' ' + produit.unite,
				                      '<a href="#" class="btn btn-xs btn-block btn-default add-qte" data-produit="' + produit.id + '" data-unite="' + produit.unite + '" data-quantite="' + produit.quantite + '" data-prix="' + produit.prix + '"><span class="glyphicon glyphicon-plus-sign"></span></a>',
				                      $.number(produit.prix, 2, ',', ' ' ) + ' €',
				                      $.number(lPrixPaye, 2, ',', ' ' ) + ' €',
				                      '<a href="#" class="btn btn-xs btn-block btn-default delete-row"><span class="glyphicon glyphicon-remove"></span></a>'
				                 ] ).draw().node();

				// Suppression de la ligne
				$(lNode).find(".delete-row").click(function(e) { 					
			  		$prototype.remove();
			  		lDataTable.row( $(this).parents('tr') ).remove().draw();
			  		e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				// Ajout de quantité
				$(lNode).find(".add-qte").click(function(e) { 			
					majQuantite(lEnteteNomChamp, response, lTaux, true);
					e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				// Ajout de quantité
				$(lNode).find(".delete-qte").click(function(e) { 			
					majQuantite(lEnteteNomChamp, response, lTaux, false);
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
	
	function majQuantite(lEnteteNomChamp, response, lTaux, lAjout) {
		var produit = response.produit;
		var lQuantite = 0;
		if(lAjout) {
			lQuantite = (
					parseFloat($(lEnteteNomChamp + '_quantite').val())
				+ 
					parseFloat(produit.quantite)
				).toFixed(2);
		} else {
			lQuantite = (
					parseFloat($(lEnteteNomChamp + '_quantite').val())
				-
					parseFloat(produit.quantite)
				).toFixed(2);
		}
		
		// Pas de Quantité négative
		if(lQuantite > 0) {
			// Si la nouvelle quantité dépasse la limite max
			if(response.produit.limites[0]
				&& response.produit.limites[0].quantite_max != "" 
				&& parseFloat(response.produit.limites[0].quantite_max) < (parseFloat(response.quantiteAchat.total) + parseFloat(lQuantite)).toFixed(2)) {
				$("#quantite-max").modal('show');
			} 
			var lPrix = 0;
			if(lAjout) {
				lPrix =(
						parseFloat($(lEnteteNomChamp + '_prix').val())
					+ 
						parseFloat(produit.prix)
					).toFixed(2);
			} else {
				lPrix =(
						parseFloat($(lEnteteNomChamp + '_prix').val())
					- 
						parseFloat(produit.prix)
					).toFixed(2);
			}
				
			var lPrixPaye = lPrix;
			if(!produit.prix_fixe) {
				lPrixPaye = (lPrix*lTaux).toFixed(2);
			}
		
			$(lEnteteNomChamp + '_quantite').val(lQuantite);
			$(lEnteteNomChamp + '_prix').val(lPrix);
			$(lEnteteNomChamp + '_prixPaye').val(lPrixPaye);
							
			var lIndex = lDataTable.column( 0, { order: 'index' } ).data().indexOf( produit.id );
			
			lDataTable.cell( lIndex, 6 ).data($.number(lQuantite, 2, ',', ' ' ) + ' ' + produit.unite);
			lDataTable.cell( lIndex, 8 ).data($.number(lPrix, 2, ',', ' ' )+ ' €');
			lDataTable.cell( lIndex, 9 ).data($.number(lPrixPaye, 2, ',', ' ' )+ ' €');
		}

		majTotal();
	}
		
	$("#btn-submit").hide();
	$("#versement").keyup(function() {majVersement();}).number( true, 2 );
	//$("#jpi_soluxbundle_recherche_produit_codeBarre" ).blur();
	
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
			$(".ligne-total").hide();
		} else {
			$(".ligne-total").show();
		}		
	}
	
	function calculTotalCol(col) {
		
		var lTotal = 0;
		$( "input[name$='[" + col + "]']" ).each(function() {
			lTotal = (parseFloat(lTotal) + parseFloat($(this).val())).toFixed(2);			
		});
		return lTotal;
	}
	
	function majVersement() {
		var lVersement = parseFloat($("#versement").val());
		
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
	

} );