@api_new_user

Feature: Create a new user

  Scenario: Test to create a new user
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password",
        "firstName": "Damien",
        "lastName": "Riviere"
      }
      """
    And the response status code should be 201

  Scenario: Test to create a new user and after that try to create the same user again
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password",
        "firstName": "Damien",
        "lastName": "Riviere"
      }
      """
    And the response status code should be 201
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password",
        "firstName": "Damien",
        "lastName": "Riviere"
      }
      """
    And the response status code should be 400
    And the JSON should be equal to:
      """
      {
        "email": "Cette adresse email est déjà existante, veuillez en choisir une autre !"
      }
      """

  Scenario: Test to create a new user without email
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "",
        "password": "password",
        "firstName": "Damien",
        "lastName": "Riviere"
      }
      """
    And the response status code should be 400
    And the JSON should be equal to:
      """
      {
        "email": "Veuillez remplir le champ de l'adresse email !"
      }
      """

  Scenario: Test to create a new user without password
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "",
        "firstName": "Damien",
        "lastName": "Riviere"
      }
      """
    And the response status code should be 400
    And the JSON should be equal to:
      """
      {
        "password": "Veuillez remplir le champ de mot de passe !"
      }
      """

  Scenario: Test to create a new user without firstName
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password",
        "firstName": "",
        "lastName": "Riviere"
      }
      """
    And the response status code should be 400
    And the JSON should be equal to:
      """
      {
        "firstName": "Veuillez remplir le champ du prénom !"
      }
      """

  Scenario: Test to create a new user without lastName
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password",
        "firstName": "Damien",
        "lastName": ""
      }
      """
    And the response status code should be 400
    And the JSON should be equal to:
      """
      {
        "lastName": "Veuillez remplir le champ du nom !"
      }
      """

  Scenario: Test to create a new user with empty fields
    When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "",
        "password": "",
        "firstName": "",
        "lastName": ""
      }
      """
    And the response status code should be 400
    And the JSON should be equal to:
      """
      {
        "email": "Veuillez remplir le champ de l'adresse email !",
        "password": "Veuillez remplir le champ de mot de passe !",
        "firstName": "Veuillez remplir le champ du prénom !",
        "lastName": "Veuillez remplir le champ du nom !"
      }
      """

  Scenario: Test to create a new user with a wrong email
    When I send a "POST" request to "/api/users" with body:
    """
    {
      "email": "damiengmail.com",
      "password": "password",
      "firstName": "Damien",
      "lastName": "Riviere"
    }
    """
    And the response status code should be 400
    And the JSON should be equal to:
    """
    {
      "email": "Veuillez entrer une adresse email valide !"
    }
    """

  Scenario: Test to create a new user with a firstName with number
    When I send a "POST" request to "/api/users" with body:
    """
    {
      "email": "damien@gmail.com",
      "password": "password",
      "firstName": "Damien64",
      "lastName": "Riviere"
    }
    """
    And the response status code should be 400
    And the JSON should be equal to:
    """
    {
      "firstName": "Votre prénom ne doit contenir que des lettres !"
    }
    """

  Scenario: Test to create a new user with a lastName with number
    When I send a "POST" request to "/api/users" with body:
    """
    {
      "email": "damien@gmail.com",
      "password": "password",
      "firstName": "Damien",
      "lastName": "Riviere64"
    }
    """
    And the response status code should be 400
    And the JSON should be equal to:
    """
    {
      "lastName": "Votre nom ne doit contenir que des lettres !"
    }
    """

  Scenario: Test to create a new user with a password with 3 characters
    When I send a "POST" request to "/api/users" with body:
    """
    {
      "email": "damien@gmail.com",
      "password": "123",
      "firstName": "Damien",
      "lastName": "Riviere"
    }
    """
    And the response status code should be 400
    And the JSON should be equal to:
    """
    {
      "password": "Votre mot de passe ne doit pas contenir moins de 4 caractères !"
    }
    """

