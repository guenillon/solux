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
	         { "orderable": false, "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] }
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
	
	var lPrixProduit = [];
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
				
				var $prototype = $($("#jpi_soluxbundle_achat_detail").attr('data-prototype')
						.replace(/__name__label__/g, produit.nom)
					    .replace(/__name__/g, produit.id))
				$prototype.find(lEnteteNomChamp + '_produit').val(produit.id);
				$prototype.find(lEnteteNomChamp + '_quantite').val(produit.quantite);
				
				var lPrixPaye = produit.prix;
				if(!produit.prix_fixe) {
					lPrixPaye = (parseFloat(produit.prix)*lTaux).toFixed(2);
				}
				
				var lNode = lDataTable.row.add( [ produit.id,
				                      produit.categorie.nom,
				                      produit.nom,
				                      produit.quantite + ' ' + produit.unite,
				                      produit.prix,
				                      '<a href="#" class="btn btn-xs btn-block btn-default delete-qte"><span class="glyphicon glyphicon-minus-sign"></span></a>',
				                      produit.quantite + ' ' + produit.unite,
				                      '<a href="#" class="btn btn-xs btn-block btn-default add-qte" data-produit="' + produit.id + '" data-unite="' + produit.unite + '" data-quantite="' + produit.quantite + '" data-prix="' + produit.prix + '"><span class="glyphicon glyphicon-plus-sign"></span></a>',
				                      produit.prix,
				                      lPrixPaye, 
				                      '<a href="#" class="btn btn-xs btn-block btn-default delete-row"><span class="glyphicon glyphicon-remove"></span></a>'
				                 ] ).draw().node();
				
				
				lPrixProduit[produit.id] = produit.prix;
				
				$("#ligne-total").show();
				
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
					majQuantite(lEnteteNomChamp, response, lTaux, true);
					majTotal();
					e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				// Ajout de quantité
				$(lNode).find(".delete-qte").click(function(e) { 			
					majQuantite(lEnteteNomChamp, response, lTaux, false);
					majTotal();
					e.preventDefault(); // évite qu'un # apparaisse dans l'URL
				    return false;
			  	});
				
				$("#jpi_soluxbundle_achat_detail").after($prototype);

			}
			majTotal();
			
			
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
						parseFloat(lPrixProduit[produit.id])
					+ 
						parseFloat(produit.prix)
					).toFixed(2);
			} else {
				lPrix =(
						parseFloat(lPrixProduit[produit.id])
					- 
						parseFloat(produit.prix)
					).toFixed(2);
			}
				
			var lPrixPaye = lPrix;
			if(!produit.prix_fixe) {
				lPrixPaye = (lPrix*lTaux).toFixed(2);
			}
		
			$(lEnteteNomChamp + '_quantite').val(lQuantite);
							
			var lIndex = lDataTable.column( 0, { order: 'index' } ).data().indexOf( produit.id );
			
			lDataTable.cell( lIndex, 6 ).data(lQuantite + ' ' + produit.unite);
			lDataTable.cell( lIndex, 8 ).data(lPrix);
			lDataTable.cell( lIndex, 9 ).data(lPrixPaye);
			lPrixProduit[produit.id] = lPrix;
		}
	}
		
	$("#ligne-total").hide();
	$("#versement").keyup(function() {majVersement();});
	
	function majTotal() {
		var lTotal = calculTotalCol(8);
		$("#total").text(lTotal);		
		$("#total-paye").text(calculTotalCol(9));		
		majVersement();
		
		var lMontantMax = parseFloat($("#montantMax").data('montantmax'));
		
		if(lMontantMax != -1 && lTotal > lMontantMax) {
			$("#montant-max").modal('show');
		}
		
		if(lTotal == 0) {
			$("#ligne-total").hide();
		}		
	}
	
	function calculTotalCol(col) {
		var lData = lDataTable.column( col ).data();
		if(lData.length === 0 ) {
			return 0;
		} else {
			return lData.reduce( function (a,b) {return (parseFloat(a) + parseFloat(b)).toFixed(2);} );
		}
	}
	
	function majVersement() {
		var lVersement = parseFloat($("#versement").val());
		
		var lRendre = 0;
		if(!isNaN(lVersement)) {
			var lTotalPaye = calculTotalCol(9);
			lRendre = (lVersement - lTotalPaye).toFixed(2);
			if(lRendre < 0) {
				lRendre = 0;
			}
		}
		$("#rendre").html(lRendre);
	}
	

} );