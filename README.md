# Rapleaf CodeIgniter Spark
This CodeIgniter Spark will allow you to query Rapleaf's API for personal information on people based off of their email address and/or postal information.

Available query fields: _'email', 'first', 'middle', 'last', 'street', 'city', 'state', 'zip4'_

The examples below demonstrate a response with a free RapLeaf account, more fields are available with a premium account.

See config/rapleaf to:

*   change email encoding options (sha1, md5, url)
*   insert your own api key (See http://www.rapleaf.com/ to obtain a free key)
*   turn debug mode off/on
*   turn display of available fields off/on

### Loading the Spark
Before using, the spark must be loaded like any other spark

	$this->load->spark('rapleaf/0.0.1');

### Single Person Query

__Request information by email address__

    $this->rapleaf->personalize('youremail@address.com');

__Request information by multiple fields (better results)__

    $query = array('first' => 'John', 'last' => 'Doe', 'email' => 'johndoe@example.com');
    $this->rapleaf->personalize($query);

__Example Response__

    Array
    (
        [age] => 25-34
        [gener] => Male
        [city] => San Francisco
        [state] => California
        [country] => United States
    )

### Bulk Requests

__Request information by multiple fields__
	
    $query = array(
         array('first' => 'John', 'last' => 'Doe', 'email' => 'johndoe@example.com'),
         array('first' => 'Jane', 'last' => 'Doe', 'email' => 'janedoe@example.com')
    );

    $this->rapleaf->personalize_bulk($query);

__Example Response__

    Array
    (
    [0] => Array
        (
            [age] => 25-34
            [gener] => Male
            [city] => San Francisco
            [state] => California
            [country] => United States
        )

    [1] => Array
        (
            [age] => 25-34
            [gener] => Female
            [city] => Seattle
            [state] => Washington
            [country] => United States
        )
    )