
$(document).ready(function () {
    var panier = [];
    var nombreProduitsPanier = $(".nombreProduitsPanier");

    $(".ajouter-au-panier").click(function () {
        var produit = $(this).data('nom');
        var prix = $(this).data('prix');
        var image = $(this).data('image');

        console.log(produit);

 

        
        

        // Ajouter les données au panier
        panier.push({ produit: produit, prix: prix, image: image ,quantite: 1});
        
        // Mettre à jour le nombre d'éléments dans le panier
        nombreProduitsPanier.text(panier.length);

        var message = $("<div>").addClass("message-ajout-panier").text("Le produit '" + produit + "' a été ajouté au panier.");

        // Ajoutez le message à la page
        $("body").append(message);
       
    
        // Supprimez le message après un délai de 3 secondes (3000 millisecondes)
        setTimeout(function() {
            message.remove();
        }, 3000);

        // Afficher le contenu du panier
        afficherPanier();
    });

    function afficherPanier() {
        var panierElement = $("#panier tbody");

        // Efface le contenu précédent du panier
        panierElement.empty();

        // Parcours du panier pour afficher les produits
        panier.forEach(function (item) {
            var lignePanier = $("<tr>");
        
            var celluleImage = $("<td>");
            celluleImage.append($("<img>").attr("src", item.image).addClass("img-fluid product-thumbnail"));
            lignePanier.append(celluleImage);
        
            var celluleNom = $("<td>").text(item.produit);
            lignePanier.append(celluleNom);
        
            var cellulePrix = $("<td>").text(item.prix + " Fcfa");
            lignePanier.append(cellulePrix);
        
            // Créez la cellule de quantité et l'input
            var celluleQuantite = $("<td>");
            var quantiteInput = $("<input>").attr("type", "number").attr("value", 1).attr("min", 1).addClass("quantity-input");

            // Créez la cellule de prix total
            var celluleTotal = $("<td>").text(item.prix + " Fcfa");

            // Ajoutez l'input à la cellule de quantité
            celluleQuantite.append(quantiteInput);

            // Ajoutez la ligne au tableau
            lignePanier.append(celluleQuantite);
            lignePanier.append(celluleTotal);

            // Écoutez les changements de valeur de l'input
            quantiteInput.on("input", function() {
                // Récupérez la nouvelle valeur de l'input
                var nouvelleQuantite = parseInt($(this).val(), 10);
                
                // Vérifiez si la valeur est un nombre valide
                if (!isNaN(nouvelleQuantite)) {
                    // Calculez le prix total en multipliant la quantité par le prix de l'item
                    var prixTotal = nouvelleQuantite * item.prix;
                    
                    // Mettez à jour le texte de la cellule de prix total
                    celluleTotal.text(prixTotal + " Fcfa");
                }
            });

        
            var celluleAction = $("<td>");
            var supprimerBouton = $("<a>").attr("href", "#").addClass("btn btn-black btn-sm delete-item").text("X");
           
            celluleAction.append(supprimerBouton);
            lignePanier.append(celluleAction);
        
            panierElement.append(lignePanier);
        
            
        });



        function handleQuantityUpdate(item, quantiteInput) {
            return function () {
                // Gestionnaire pour le bouton "-"
                lignePanier.find(".decrease").click(function () {
                    var inputQuantite = $(this).closest(".quantity-container").find(".quantity-amount");
                    var quantite = parseInt(inputQuantite.val());

                    if (quantite > 1) {
                        quantite--;
                        inputQuantite.val(quantite);
                        mettreAJourQuantiteProduit(item, quantite); // Mettre à jour la quantité dans le panier
                        mettreAJourTotalPanier(); // Mettre à jour le total du panier
                    }
                });

                // Gestionnaire pour le bouton "+"
                lignePanier.find(".increase").click(function () {
                    var inputQuantite = $(this).closest(".quantity-container").find(".quantity-amount");
                    var quantite = parseInt(inputQuantite.val());

                    quantite++;
                    inputQuantite.val(quantite);
                    mettreAJourQuantiteProduit(item, quantite); // Mettre à jour la quantité dans le panier
                    mettreAJourTotalPanier(); // Mettre à jour le total du panier
                });

            };
        }




        function mettreAJourTotalPanier() {
            var totalDuPanier = 0;
            $(".total-cell").each(function () {
                var prix = parseFloat($(this).closest("tr").find(".prix-cell").text().replace(" Fcfa", ""));
                var quantite = parseInt($(this).closest("tr").find(".quantity-input").val());
                totalDuPanier += prix * quantite;
                return totalDuPanier
            });
        
            // Mettez à jour l'affichage du total du panier
            $("#total-panier").text(totalDuPanier.toFixed(2) + " Fcfa");
        }


           

        


        // Gestionnaire pour le bouton "X"
        $("#panier").on("click", ".delete-item", function (event) {
            event.preventDefault();
            var lignePanier = $(this).closest("tr"); // Trouver la ligne du panier parente
            var index = lignePanier.index(); // Obtenir l'indice de la ligne dans le tableau
            var item = panier[index]; // Récupérer l'élément correspondant du panier
        
            // Soustraire le prix total de l'élément supprimé du total du panier
            var prixTotalElement = item.prix * item.quantite;
            totalDuPanier -= prixTotalElement;
        
            // Supprimer l'élément correspondant du panier
            panier.splice(index, 1);
        
            // Mettre à jour l'affichage du total du panier
            $("#total-panier").text(totalDuPanier.toFixed(2) + " Fcfa");
        
            lignePanier.remove(); // Supprimer la ligne du panier de l'interface utilisateur
            mettreAJourNombreProduitsPanier(); // Mettre à jour le nombre d'éléments dans le panier
            recalculerTotalEnFonctionQuantite();
        });


        function mettreAJourNombreProduitsPanier() {
            var nombreProduits = $("#panier tbody tr").length;
            $(".nombreProduitsPanier").text(nombreProduits);
        }

            
        
        // Initialisez le total du panier au chargement de la page
        var totalDuPanier = 0; // Ajoutez cette variable en dehors de la fonction
        
        // Fonction pour recalculer le total en fonction des quantités des produits
        function recalculerTotalEnFonctionQuantite() {
            totalDuPanier = 0; // Réinitialisez le total du panier
            // Parcourez le panier pour calculer le total en fonction de la quantité
            panier.forEach(function (item) {
                totalDuPanier += item.prix * item.quantite;
            });
        
            // Mettez à jour le texte du sous-total avec le total calculé
            $("#subtotal").text(totalDuPanier + " Fcfa");
        
            // Mettez à jour le texte du total avec le même montant du sous-total
            $("#total").text(totalDuPanier + " Fcfa");
        }
        
        // Gestionnaire d'événements pour les changements de quantité
        $(".quantity-input").on("input", function () {
            var nouvelleQuantite = parseInt($(this).val(), 10);
            var lignePanier = $(this).closest("tr");
            var index = lignePanier.index();
        
            // Mettez à jour la quantité dans le panier
            panier[index].quantite = nouvelleQuantite;
        
            // Appelez la fonction pour recalculer le total en fonction des quantités
            recalculerTotalEnFonctionQuantite();
        });
        
        // Initialisez le total du panier au chargement de la page
        recalculerTotalEnFonctionQuantite();
        
            

    }
    

    $('#PanierProduit').hide();
    $('#facture').hide();
    
                   
    $("#logoPanier").click(function () {
        $('#indexProduit').hide();
        $('#facture').hide();
        $('#PanierProduit').show();

       
    });

    $(".navbar-brand").click(function () {
        $('#indexProduit').show();
        $('#facture').hide();
        $('#PanierProduit').hide();

       
    });


    $("#Pyfacture").click(function () {
        $('#indexProduit').hide();
        $('#facture').show();
        $('#PanierProduit').hide();

       
    });

    
    
    
    
    
    
    
       
 

    


 
});








               


               
                   
               
               
                  
   
                   
   
                   
               
              
               
               
           






