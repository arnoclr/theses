Classes :

<!-- Classe qui represente une these -> vient de pdo

Classe pour les requetes sur les theses avec des méthodes pratiques de recherche -->

Classe app -> gérer les urls

/
/search?q=

Classe pdo (par defaut, generée dans la classe app)

Classe pour la recherche -> renvoie des objets theses
Parse la recherche


## Recherches

Chaine de recherche <Filtres?> 

filtre:valeur / :valeur

| Clé   | Valeurs possibles  |
|-------|--------------------|
| avant | <number>           |
| apres | <number>           |
| en    | <number>           |
| à     | <string>           |
| par   | <string>           |
| tri   | "recent", "ancien" |
|       | "enligne"          |

## Datas a afficher :

Nombre de résultats -> nombre
Nombre de résultats par année -> courbe
Theses triées par pertinence -> liste 
Liste des établissements concernés dans l'odre décroissant -> liste
Liste des disciplines concernées -> chips
Liste des sources qui reviennent le plus ->liste
Sujets qui reviennent le plus -> chips
Liste avec les langues les plus utilisées -> liste avec drapeaux (champ langue iso 639 1 : google charts)
Carte des départements les plus concernés -> carte (google charts)
    récuperer code établissement -> faire le lien avec le departement
    Afficher les universités au clic sur chaque région (liste de celles qui reviennent le plus)
Liste des classifications des thèses qui reviennent le plus -> camembert / ou liste (Classification Dewey des thèses - sets OAI-PMH)


https://jsfiddle.net/gh/get/jquery/1.11.0/highslide-software/highcharts.com/tree/master/samples/mapdata/countries/fr/fr-all


## design

exemples de 3 requetes à l'entrée du site
https://trends.google.fr/trends/explore?geo=FR&q=php,javascript

## To do

- [ ] Recherches tendances (demander accord rgpd, stocker pendant max 30j)
- [ ] thèmes tendances (basés sur les recherches tendances)