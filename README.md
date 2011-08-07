## CodeIgniter Rapleaf Spark
This CodeIgniter Spark will allow you to query Rapleaf's API for personal information on people based off of their email address and/or postal information.

Available query fields: _'email', 'first', 'middle', 'last', 'street', 'city', 'state', 'zip4'_

The examples below demonstrate a response with a free RapLeaf account, more fields are available with a premium account.

See config/rapleaf to:

*   change email encoding options (sha1, md5, url)
*   insert your own api key (See http://www.rapleaf.com/ to obtain a free key)
*   turn debug mode off/on
*   turn display of available fields off/on

#### Request information by email address
    $this->rapleaf->personalize('youremail@address.com');

#### Request information by multiple fields (better results)
    $query = array('first' => 'John', 'last' => 'Doe', 'email' => 'johndoe@example.com');
    $this->rapleaf->personalize($query);

#### Example Response

    Array
    (
        [age] => 25-34
        [gener] => Male
        [city] => San Francisco
        [state] => California
        [country] => United States
    )


#### Request information by multiple fields

    $query = array(
         array('first' => 'John', 'last' => 'Doe', 'email' => 'johndoe@example.com'),
         array('first' => 'Jane', 'last' => 'Doe', 'email' => 'janedoe@example.com')
    );

    $this->rapleaf->personalize_bulk($query);

#### Example Response

    Array
    (
    [0] => Array
        (
            [age] => 25-34
            [gener] => Male
            [city] => SanFrancisco
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