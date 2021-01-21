@api_show_user_list

  Feature: Show user list

    Background: Load fixtures
      When I load my admin
      And I need parameter "UuidEncoded" from user "admin@gmail.com"
      And I save it into "ADMIN_UUID"
      And I need parameter "Slug" from user "admin@gmail.com"
      And I save it into "ADMIN_SLUG"
      When I load my user
      When I load my users

      Scenario: Test a page who doesn't exist
        When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "GET" request to "/api/users?page=10" with body:
        """
        {
        }
        """
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON should be equal to:
        """
        {
            "message": "Cette page n'existe pas !"
        }
        """

      Scenario: Test to access to user list without an admin account
        When After authentication on url "/api/login_check" with method "POST" as email "marc@gmail.com" with password "password", I send a "GET" request to "/api/users" with body:
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

      Scenario: Test all user nodes
        When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "GET" request to "/api/users" with body:
        """
        {
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0].email" should exist
        And the JSON node "root[0].firstName" should exist
        And the JSON node "root[0].lastName" should exist
        And the JSON node "root[0].createdAt" should exist
        And the JSON node "root[0].slug" should exist
        And the JSON node "root[0].uuidEncoded" should exist
        And the JSON node "root[0]._link" should exist

      Scenario: Test _link nodes
        When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "GET" request to "/api/users" with body:
        """
        {
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0]._link.self" should exist
        And the JSON node "root[0]._link.self.href" should exist
        And the JSON node "root[0]._link.self.href" should contain "/api/users/<<ADMIN_UUID>>/<<ADMIN_SLUG>>"
        And the JSON node "root[0]._link.delete" should exist
        And the JSON node "root[0]._link.delete.href" should exist
        And the JSON node "root[0]._link.delete.href" should contain "/api/users/<<ADMIN_UUID>>/<<ADMIN_SLUG>>"
        And the JSON node "root[0]._link.first" should exist
        And the JSON node "root[0]._link.first.href" should exist
        And the JSON node "root[0]._link.first.href" should contain "/api/users?page=1"
        And the JSON node "root[0]._link.last" should exist
        And the JSON node "root[0]._link.last.href" should exist
        And the JSON node "root[0]._link.last.href" should contain "/api/users?page=6"
        And the JSON node "root[0]._link.next" should exist
        And the JSON node "root[0]._link.next.href" should exist
        And the JSON node "root[0]._link.next.href" should contain "/api/users?page=2"
        And the JSON node "root[0]._link.prev" should not exist

      Scenario: Test _link.prev
        When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "GET" request to "/api/users?page=2" with body:
        """
        {
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0]._link.prev" should exist
        And the JSON node "root[0]._link.prev.href" should exist
        And the JSON node "root[0]._link.prev.href" should contain "/api/users?page=1"

      Scenario: Test _link.next
        When After authentication on url "/api/login_check" with method "POST" as email "admin@gmail.com" with password "password", I send a "GET" request to "/api/users?page=6" with body:
        """
        {
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node "root[0]._link.next" should not exist


