<!-- constraints, formalism 
    - no more than 20 minutes
    - table of contents at the beginning of the teaching aid
    - link to the teaching aid included
-->

# Qu'est-ce que le MVC ?

## Présentation générale

### la notion de design pattern

Tout d'abord, le MVC est un patron (ou pattern) de conception de logiciel fait pour le web.

Un pattern de conception logiciel est, schématiquement, une manière d'écrire et d'organiser le software que l'on développe afin de répondre à des exigences de maintenabilité du code et de performance. Il y en a quantité, [n'hésitez pas à vous y familiariser](https://phptherightway.com/pages/Design-Patterns.html) !

En suivant un design pattern particulier, on va décomposer son code en entités logiques qui ont chacune un rôle bien précis. Cette idée est dérivée d'un principe très important en programmation: **la séparation des responsabilités (separation of concerns)**.

### le pattern MVC

MVC est un acronyme pour `Modèle Vue Contrôleur`. Ces trois éléments sont les composants de ce pattern. Séparer son code afin de le distribuer entre ces trois composants répond principalement au but de **séparer la représentation des données en interne de la manière dont cette même donnée est représentée à l'utilisateur final**.

![WTF](./images/wtf.gif)

Derrière cette formulation barbare, l'idée est simple à comprendre:  

Prenons une base de données d'utilisateurs au sein d'une organisation X en exemple. Elle va contenir des enregistrements tels que =>

```txt
------------------------------------------------------------------
- id -- username --      email        --       password          -
------------------------------------------------------------------
------------------------------------------------------------------
  1  -- janedoe  -- johndoe@gmail.com -- a_very_strong_password  -
------------------------------------------------------------------
------------------------------------------------------------------
  2  -- janedoe  -- janedoe@gmail.com -- another_strong_password -
------------------------------------------------------------------
```

Maintenant, imaginons qu'un administrateur de cette organisation souhaite lister les emails de ces utilisateurs depuis un portail web. Va-t-on, en tant que développeur de ce portail web, lui permettre d'afficher toutes les informations utilisateurs, y compris le mot de passe, sur le portail web ? Certainement pas !

C'est ce que signifie **séparer la représentation des données en interne de la manière dont cette même donnée est représentée à l'utilisateur final** :

- la donnée est stockée selon une certaine représentation en base de données
- l'information finale affichée à l'utilisateur est formatée, filtrée, par l'application

Le pattern MVC vise à faciliter ce processus.

## M pour Modèle

C'est la partie de l'application qui fait le lien avec la base de données, un modèle représente une entité de l'application (`Utilisateur`, `Article`, `Commande`, etc.). C'est ce qu'on appelle un `blueprint`, en programmation orientée objet, de ce qu'est telle entité.

A chaque fois que l'on va récupérer un enregistrement en base de données de telle entité, par exemple un utilisateur, on va créer un objet PHP (par exemple) de type `Utilisateur`. C'est depuis cet objet qu'on va récupérer la représentation finale de l'information affichée à l'utilisateur de l'application.

Dans la continuité de l'exemple précédent sur les passwords, voici un exemple de ce qu'on peut trouver dans une application Laravel, pour cacher certains attributs d'une entité à l'utilisateur final:

```php
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class User extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];
}
```

C'est ainsi que ce framework, au niveau de la couche modèle, va protéger certaines informations, présentes en base de données, mais qui ne seront pas rendues visibles à l'utilisateur final.

C'est au niveau de la couche modèle que vous allez coder la `business logic` (loqique métier) de votre application. Par exemple, dans une application de gestion de commandes, vous allez avoir une classe `Commande` qui va représenter un enregistrement de la table `commandes`, avec toutes ses subtilités: la commande a déjà été payée ? la commande a déjà été livrée ? et bien d'autres choses...

### le cas de Symfony

Symfony est un framework, c'est à dire un ensemble de composants qui permettent de développer des applications web rapidement. Originellement, il se présentait comme un framework MVC mais, avec le temps, sa nature modulaire a fait reformuler à ses créateurs cette caractéristique initiale, [extrait de la documentation officielle](https://symfony.com/doc/current/create_framework/introduction.html):

```txt
Many modern web frameworks advertise themselves as being MVC frameworks. This tutorial won't talk about the MVC pattern, as the Symfony Components are able to create any type of frameworks, not just the ones that follow the MVC architecture. Anyway, if you have a look at the MVC semantics, this book is about how to create the Controller part of a framework. For the Model and the View, it really depends on your personal taste and you can use any existing third-party libraries (Doctrine, Propel or plain-old PDO for the Model; PHP or Twig for the View).

When creating a framework, following the MVC pattern is not the right goal. The main goal should be the Separation of Concerns; this is probably the only design pattern that you should really care about. The fundamental principles of the Symfony Components are focused on the HTTP specification. As such, the framework that you are going to create should be more accurately labelled as a HTTP framework or Request/Response framework.
```

En résumé: vous pouvez monter une application qui suit le pattern MVC avec Symfony, mais vous êtes parfaitement libre de choisir d'autres designs patterns.

## C pour Contrôleur

Maintenant parlons de la couche contrôleur. Vous allez souvent lire que le contrôleur est responsable de la *logique applicative*, à différencier de la *business logic*, de votre application.

Avant de discuter de ce qu'est un contrôleur, il est très important que vous compreniez la différence entre logique métier et logique applicative dans une application. Pour ce faire, nous allons tenter de bien différencier les deux par un jeu de questions/réponses qui vous permettra de bien appréhender la différence.

Imaginons que nous soyons dans le cas de notre application de gestion de commandes avec, notamment une base de données utilisateurs, vous **allez devoir me dire, pour chaque question ou affirmation, si cela relève de la logique métier ou de la logique applicative**:

- une requête HTTP pour créer une commande sur le endpoint `/api/commandes` doit être une requête POST
- seul un utilisateur actif depuis 3 mois peut bénéficier d'une réduction
- l'API doit renvoyer un code HTTP 400 si le frontend ne lui envoie pas de JSON valide
- je suis un administrateur de l'application, dois-je pouvoir voir le mot de passe de l'utilisateur ?
- est-ce que j'enregistre le paiement de la commande au moment où la livraison a démarré ?

Vous l'aurez compris, la couche contrôleur ne se soucie pas du besoin métier de votre application dans le monde réel, elle n'est concernée que par diverses validations techniques pour répondre à des requêtes HTTP.

API ? requête HTTP ? frontend ? JSON beaucoup de nouveaux concepts...

![lots of questions](./images/lots_of_questions.gif)

Ces notions ne sont pas l'objet de cette présentation, mais tout ce que vous devez comprendre, c'est que la couche contrôleur adresse justement ces problématiques techniques dans votre application: à savoir quelle réponse envoyer à une requête entrante dans votre application depuis le Web. Vous devez juste savoir que la réponse est une réponse HTTP puisque c'est le protocole qu'utilise votre navigateur web (Chrome, Firefox, etc.).

Toujours dans notre application de gestion de commandes, quand un utilisateur clique sur "commander", le premier object concerné par la réponse finale qu'il va recevoir (une page de confirmation de la commande) est le contrôleur.

### ce qu'est un contrôleur en une phrase

Dans le cadre d'une application web PHP, le contrôleur est la classe qui va renvoyer une réponse HTTP à l'utilisateur suite à sa requête.

## comment il remplit sa mission

Dans notre application de gestion de commandes, par exemple, le contrôleur va déléguer au modèle la tâche de récupérer les données de la base de données pour créer une commande, l'associer à un utilisateur, renseigner son statut, etc.

Analysons un contrôleur Symfony typique:

```php
// src/Controller/OrderController.php
namespace App\Controller;

// ...
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="create_order")
     */
    public function createOrder(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $order = new Order();
        $order->setName('Keyboard');
        $order->setPrice(1999);
        $order->setDescription('Ergonomic and stylish!');

        $entityManager->persist($order);
        $entityManager->flush();

        return new Response(
            '<p>Saved new order with id '.$order->getId().'</p>',
            Response::HTTP_OK,
            ['content-type' => 'text/HTML']
        );
    }
}
```

### un contrôleur peut renvoyer plusieurs types de réponses

Un contrôleur peut renvoyer au choix une réponse HTTP, une réponse JSON, une réponse XML, une réponse HTML, une réponse de type `text/plain`, etc.

Son rôle n'est pas limité à un type de réponse !

Bien sûr, comme nous sommes en train de parler de MVC, nous allons nous focaliser sur un contrôleur qui va renvoyer du HTML pour répondre à la requête de l'utilisateur final sur le Web, pourquoi ? parce que la couche `Vue` du pattern est constitué par ce que voit cet utilisateur sur son navigateur.

## la couche Vue

<!-- TODO -->