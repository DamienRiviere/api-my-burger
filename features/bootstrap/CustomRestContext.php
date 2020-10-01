<?php

use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext;

class CustomRestContext extends RestContext
{

	/**
	 * @When /^After authentication on url "([^"]*)" with method "([^"]*)" as user "([^"]*)" with password "([^"]*)", I send a "([^"]*)" request to "([^"]*)" with body:$/
	 * @param $arg1
	 * @param $arg2
	 * @param $arg3
	 * @param $arg4
	 * @param $arg5
	 * @param $arg6
	 * @param PyStringNode $string
	 * @return
	 */
	public function afterAuthenticationOnUrlWithMethodAsUserWithPasswordISendARequestToWithBody(
		$arg1,
		$arg2,
		$arg3,
		$arg4,
		$arg5,
		$arg6,
		PyStringNode $string
	) {
		$requestLogin = $this->request->send(
			$arg2,
			$this->locatePath($arg1),
			[],
			[],
			json_encode(
				[
					'username' => $arg3,
					'password' => (string) $arg4,
				]
			),
			['CONTENT_TYPE' => 'application/json']
		);
		$datas = json_decode($requestLogin->getContent(), true);
		$response = $this->request->send(
			$arg5,
			$this->locatePath($arg6),
			[],
			[],
			$string !== null ? $string->getRaw() : null,
			[
				'CONTENT_TYPE' => 'application/json',
				'HTTP_Authorization' => sprintf('Bearer %s', $datas['token'])
			]
		);
		return $response;
	}

	/**
	 * @When /^authentication on url "([^"]*)" with method "([^"]*)" as user "([^"]*)" with password "([^"]*)"$/
	 * @param $arg1
	 * @param $arg2
	 * @param $arg3
	 * @param $arg4
	 * @return
	 */
	public function authenticationOnUrlWithMethodAsUserWithPassword(
		$arg1,
		$arg2,
		$arg3,
		$arg4
	) {
		$requestLogin = $this->request->send(
			$arg2,
			$this->locatePath($arg1),
			[],
			[],
			json_encode(
				[
					'username' => $arg3,
					'password' => (string) $arg4,
				]
			),
			['CONTENT_TYPE' => 'application/json']
		);
		$datas = json_decode($requestLogin->getContent(), true);

		return $datas;
	}
}
