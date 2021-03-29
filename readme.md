# Transformation

A package to easily transform data using transformer classes. The idea behind this package is to be able to take data in one format and return it in a different or stripped down format. For example, you might use this when you are making an api endpoint and don't want to return everything or want to return something in multiple formats.

This is the generic version of this package. There is also a wordpress specific version here: https://github.com/elipettingale/wordpress-transformation

## How to Install

Just install the package using composer:

    composer require elipettingale/transformation
    
## How to Use

First start by creating a transformer class:

    class UserTransfomer extends Transformer
    {
    
    }
    
Then use the Transform class to transform some data:

    $users = [
        [
            'first_name' => 'Dave',
            'last_name' => 'Test',
            'email' => 'dave@test.com'
        ],
        [
            'first_name' => 'Gina',
            'last_name' => 'Test',
            'email' => 'gina@test.com'
        ]
    ];
    
    $users = Transform::all($users, UserTransfomer::class);

Based on the configuration defined in your transformer class the data will be manipulated and returned

## Building a Transformer Class

There are a few tools that you can use to transform your data, these can be defined in your transformer classes.

### Includes or Excludes

These are properties you can define to determine which attributes are returned. 

Defining includes will mean that only attributes that you define will be returned, for example the following transformer:

    class UserTransfomer extends Transformer
    {
        protected $includes = [
            'first_name'
        ];
    }

would mean that using our earlier example we now have the following in $users:

    [
        [
            'first_name' => 'Dave'
        ],
        [
            'first_name' => 'Gina'
        ]
    ]
    
Defining excludes will mean that all attributes will be returned except for those that you define, for example the following transformer:

    class UserTransfomer extends Transformer
    {
        protected $excludes = [
            'first_name'
        ];
    }
    
would now transform our $users to:

    [
        [
            'last_name' => 'Test',
            'email' => 'dave@test.com'
        ],
        [
            'last_name' => 'Test',
            'email' => 'gina@test.com'
        ]
    ]
    
If you define neither then all attributes will be returned.
    
### Renames

Don't like the name of an attribute? You can use renames to change the key of attributes:

    class UserTransfomer extends Transformer
    {
        protected $includes = [
            'first_name'
        ];
        
        protected $rename = [
            'first_name' => 'name'
        ];
    }
    
will now return:

    [
        [
            'name' => 'Dave'
        ],
        [
            'name' => 'Gina'
        ]
    ]

### Mutators

If you've ever worked with Eloquent you should recognise this. A mutator is a method you define that will alter an attribute in some why before returning the data. It can also be used to define new computed attributes.

For example, take the following transformer:

    class UserTransfomer extends Transformer
    {
        public function getFullNameAttribute()
        {
            return $this->item['first_name'] . ' ' . $this->item['last_name'];
        }
    }
    
This will create a new attribute called full_name and will use your function to calculate it's value.

Now when running our users through this transformer we get:

    [
        'first_name' => 'Dave',
        'last_name' => 'Test',
        'email' => 'dave@test.com',
        'full_name' => 'Dave Test
    ],
    [
        'first_name' => 'Gina',
        'last_name' => 'Test',
        'email' => 'gina@test.com',
        'full_name' => 'Gina Test
    ]

Note that these new attributes will still obey the defined includes, excludes and renames.
