@api_show_user

  Feature: Show one user

    Background:
      When I load my user
      Given I need parameter "UuidEncoded" from user "marc@gmail.com"
      And I save it into "MARC_UUID"
      Given I need parameter "Slug" from user "marc@gmail.com"
      And I save it into "MARC_SLUG"
      Then I load my admin
      Given I need parameter "UuidEncoded" from user "admin@gmail.com"
      And I save it into "ADMIN_UUID"
      Given I need parameter "Slug" from user "admin@gmail.com"
      And I save it into "ADMIN_SLUG"

      Scenario: Test with a user who doesn't exist
        When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "GET" request to "/api/users/1/test-test" with body:
        """
        {
        }
        """
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
          "message": "Utilisateur introuvable !"
        }
        """

      Scenario: Test to access profile with another account
        When After authentication on url "/api/login_check" with method "POST" as email "marc@gmail.com" with password "password", I send a "GET" request to "/api/users/<<ADMIN_UUID>>/<<ADMIN_SLUG>>" with body:
          """
          {
          }
          """
        Then the response status code should be 403
        And the response should be in JSON
        And the JSON should be equal to:
          """
          {
              "message": "Vous n'êtes pas autorisé à accéder à cette ressource !"
          }
          """

      Scenario: Test to access profile with his own account
        When After authentication on url "/api/login_check" with method "POST" as email "marc@gmail.com" with password "password", I send a "GET" request to "/api/users/<<MARC_UUID>>/<<MARC_SLUG>>" with body:
        """
        {
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "email" should exist
        And the JSON node "firstName" should exist
        And the JSON node "lastName" should exist
        And the JSON node "createdAt" should exist
        And the JSON node "slug" should exist

      Scenario: Test to access user profile with admin account
        When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "GET" request to "/api/users/<<MARC_UUID>>/<<MARC_SLUG>>" with body:
        """
        {
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "email" should exist
        And the JSON node "firstName" should exist
        And the JSON node "lastName" should exist
        And the JSON node "createdAt" should exist
        And the JSON node "slug" should exist
        And the JSON node "_link" should exist
        And the JSON node "_link.delete" should exist
        And the JSON node "_link.delete.href" should exist
        And the JSON node "_link.delete.href" should contain "/api/users/<<MARC_UUID>>/<<MARC_SLUG>>"
        And the JSON node "_link.delete.method" should exist
        And the JSON node "_link.delete.method" should contain "DELETE"
