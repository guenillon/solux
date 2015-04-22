$(document).ready(function() {
	var lDataTable = $('.jpi_table_data_table').DataTable({
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
	
	
    var lIndex = 0;	
    var lRawIndex = [];
	
	function addProduit(produit, statusText, xhr, $form)  { 
		lock = false;
		
		if(produit != "") { // Si la recherche retourne un produit
			// Vérifier si le produit est déjà dans l'achat
			if($('#jpi_soluxbundle_achat_detail_' + produit.id + '_produit').length > 0) { // Maj de la quantité et du prix
				var lQuantite = (
							parseFloat($('#jpi_soluxbundle_achat_detail_' + produit.id + '_quantite').val())
						+ 
							parseFloat(produit.quantite)
						).toFixed(2);
				
				var lPrix = (
							parseFloat($('#jpi_soluxbundle_achat_detail_' + produit.id + '_prix').val())
						+ 
							parseFloat(produit.prix)
						).toFixed(2);
				
				$('#jpi_soluxbundle_achat_detail_' + produit.id + '_quantite').val(lQuantite);
				$('#jpi_soluxbundle_achat_detail_' + produit.id + '_prix').val(lPrix);
				
				lDataTable.row(lRawIndex[produit.id]).data( [ produit.id,
								                      produit.categorie.nom,
								                      produit.nom,
								                      produit.quantite + ' ' + produit.unite,
								                      produit.prix,
								                      lQuantite + ' ' + produit.unite,
								                      lPrix
								                 ] ).draw();
				
			} else { // Ajout du produit au formulaire
				var $prototype = $($("#jpi_soluxbundle_achat_detail").attr('data-prototype')
						.replace(/__name__label__/g, produit.nom)
					    .replace(/__name__/g, produit.id))
				$prototype.find('#jpi_soluxbundle_achat_detail_' + produit.id + '_produit').val(produit.id);
				$prototype.find('#jpi_soluxbundle_achat_detail_' + produit.id + '_quantite').val(produit.quantite);
				$prototype.find('#jpi_soluxbundle_achat_detail_' + produit.id + '_unite').val(produit.unite);
				$prototype.find('#jpi_soluxbundle_achat_detail_' + produit.id + '_prix').val(produit.prix);
				$("#jpi_soluxbundle_achat_detail").after($prototype);
				
				lDataTable.row.add( [ produit.id,
				                      produit.categorie.nom,
				                      produit.nom,
				                      produit.quantite + ' ' + produit.unite,
				                      produit.prix,
				                      produit.quantite + ' ' + produit.unite,
				                      produit.prix
				                 ] ).draw();
				
				lRawIndex[produit.id] = lIndex;
				lIndex++;
			}
		} else {
			alert("error");
		}
	}
} );