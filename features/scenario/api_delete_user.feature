@api_delete_user

Feature: API delete user

  Background: Load fixtures
    When I load my user
    And I load my admin

    Scenario: Test to delete a user with a user account who doesn't have ROLE_ADMIN
      When After authentication on url "/api/login_check" with method "POST" as email "marc@gmail.com" with password "password", I send a "DELETE" request to "/api/users/1" with body:
      """
      {
      }
      """
      Then the response status code should be 403
      And the response should be in JSON
      And the JSON should be equal to:
      """
      {
        "message": "Vous n'êtes pas autorisé à supprimer cette ressource !"
      }
      """

    Scenario: Test to delete a user with a user account with ROLE_ADMIN
      When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "DELETE" request to "/api/users/1" with body:
      """
      {
      }
      """
      Then the response status code should be 204
