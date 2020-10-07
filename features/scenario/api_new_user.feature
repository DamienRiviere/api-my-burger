@api_new_user

  Feature: Create a new user

    Scenario: Test to create a new user
      When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password"
      }
      """
      And the response status code should be 201

    Scenario: Test to create a new user and after that try to create the same user again
      When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password"
      }
      """
      And the response status code should be 201
      When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": "password"
      }
      """
      And the response status code should be 400
      And the JSON should be equal to:
      """
      {
        "email": "Cette adresse email est déjà existante, veuillez en choisir une autre !"
      }
      """

    Scenario: Test to create a new user without password
      When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "damien@gmail.com",
        "password": ""
      }
      """
      And the response status code should be 400
      And the JSON should be equal to:
      """
      {
        "password": "Veuillez entrer un mot de passe valide !"
      }
      """

    Scenario: Test to create a new user without email
      When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "",
        "password": "password"
      }
      """
      And the response status code should be 400
      And the JSON should be equal to:
      """
      {
        "email": "Veuillez entrer une adresse email valide !"
      }
      """

    Scenario: Test to create a new user with empty fields
      When I send a "POST" request to "/api/users" with body:
      """
      {
        "email": "",
        "password": ""
      }
      """
      And the response status code should be 400
      And the JSON should be equal to:
      """
      {
        "email": "Veuillez entrer une adresse email valide !",
        "password": "Veuillez entrer un mot de passe valide !"
      }
      """
