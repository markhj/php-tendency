![PHP Tendency banner](https://res.cloudinary.com/drfztvfdh/image/upload/v1731160458/opengl.it.com/Github%20splashes/php-tendency_qbl46q.jpg)

![GitHub Tag](https://img.shields.io/github/v/tag/markhj/php-tendency?label=version)
![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?label=license)

**PHP Tendency** is a random value generator. But it's not the
one you'd pick to find lottery numbers, rather it's the one you'd choose
to calculate outcomes in complex scenarios, where many factors impact the
likelihood of an outcome.

## 💫 Sounds cool, tell me more...

**PHP Tendency** originated from the development of a political simulation game,
in which the choices of people, countries and companies should have a degree of
randomness, but never be _totally_ random.

The risk that a person commits a crime _isn't_ 50/50. It's determined by personality,
criminal history, how the person was raised, life circumstances, social circles, and so forth.

Implementing such complex determination in code isn't easy, and without the proper
tooling, it's outright cumbersome. Hard to test, hard to predict, and hard to keep
in check.

This is the exact problem _PHP Tendency_ sets out to solve. Let's imagine a function
which calculates the risk of someone committing a crime. For a prototype, most would
probably tend to do something like this:

````php
class Person
{
    public function shouldCommitCrime(): bool
    {
        $risk = 0;
        if ($this->personality->integrity < 0.3) {
            $risk += 0.1;
        }
        if ($this->criminalHistory->count() > 0) {
            $risk += 0.15;
        }
        // ... And hundreds more conditions
        return $risk > 0.5;
    }
}
````

But... What if that same thing could look like this?

````php
public function shouldCommitCrime(): bool
{
    return $this->randomizer()
        ->hasLow(Personality::Integrity)
        ->hasCriminalRecord()
        // And so forth
        ->compute()
        ->result;
}
````

Much better, right? This is easier to test, easier to maintain,
and easier to read.

"_But where do all these methods like ``hasLow`` and ``hasCriminalRecord``
come from?_" What a great question! They come from extensions. Think of them
as lots of small custom modules you write specifically for your project.
We'll get back to that later in this guide. But they basically provide reusability,
modularity, testability and overview.

## 🍃 Features

- **Extensible and modular**: Create separate classes which are injected into a scenario
  on demand.
- **Biased randomization**: Make certain outcomes more likely than others using
  a weight-based randomization (mean + standard deviation).
- **Several types**: Int, float or bool? Doesn't matter. You can also build your own
  custom randomizer which takes strings, arrays, or specific classes. The sky is the limit.

## 📌 Prerequisites

- PHP 8.3 or higher

## 📦 Installation

The library is easily installed with Composer:

````bash
composer require markhj/php-tendency
````

> Submission to Packagist coming in next version.

## 🚀 Getting started

In the _Getting Started_ chapter we'll have a look at the fundamental
usage of _PHP Tendency_. Take note that the really fun stuff comes in the
**Extensions** chapter.

There are a set of base classes called "randomizers".
Three are bundled out-of-the-box with _PHP Tendency_, but you can
easily build more on your own, simply by extending ``RandomBase``.

### Boolean

To retrieve a random boolean value:

````php
use Markhj\PhpTendency\RandomBool;
````

`````php
(new RandomBool())->compute();
`````

### Integer

Retrieve a random integer between a min and max.

````php
use Markhj\PhpTendency\RandomInt;
````

`````php
(new RandomInt(15, 35))->compute();
`````

### Float

Retrieve a random floating-point value between a min and max.

````php
use Markhj\PhpTendency\RandomInt;
````

`````php
(new RandomInt(-25.0, 25.0))->compute();
`````

### Bias

With all of these classes, you can use ``changeMean``, which
moves the bias.

````php
(new RandomBool())->changeMean(-0.25);  // More likely to be false
(new RandomBool())->changeMean(0.25);   // More like to be true
(new RandomInt(0, 100))->changeMean(0.25);  // More like to land around 75
````

Keep in mind that ``changeMean`` isn't a _setter_, it increments or decreases.

> The mean value starts at ``0.5``, and is intended to be between
> 0 and 1 for most use-cases. But this boundary isn't strictly enforced.

### The ``RandomizedResult`` class

All the randomization classes return an instance of
``RandomizedResult`` instead of a final value. It provides
some information on top of the computed random value.
These values are mainly useful for testing, but should you
have some reason to use them... Well, they are there.

What it contains is:

| Property     | Type      | Description                                                                        |
|--------------|-----------|------------------------------------------------------------------------------------|
| ``mean``     | ``float`` | The final mean value used after extensions have manipulated it.                    |
| ``computed`` | ``float`` | The final computed random value (between 0 and 1).                                 |
| ``result``   | ``mixed`` | The actual result, typically a number between X and Y, boolean, or something else. |


### Standard deviation

You can modify the standard deviation on all of the above classes.
The standard deviation is an expression of "how far" a random
value _typically_ falls from the mean.

Example:

````php
(new RandomFloat(10.0, 25.0, 0.25))->compute();
````

Here, the standard deviation is ``0.25``` which corresponds
to 25% from the mean value.

> The standard deviation must be given between 0 and 1.

Learn more:
[Standard deviation on Wikipedia](https://en.wikipedia.org/wiki/Standard_deviation)

## 💡 Extensions

Okay, so what we've seen so far is pretty dull. But it's necessary
to know it, to get to the fun part -- which we have finally reached.

If the classes presented so far are the heart of _PHP Tendency_,
then extensions are the brain, liver, kidney and spleen.
_PHP Tendency_ makes only limited sense without them.

### The idea

The ultimate goal of an extension is to manipulate the mean value
contained in the randomizer (.e.g ``RandomBool`` or ``RandomFloat``).

The mean value always starts at ``0.5`` (perfectly between ``0.0``
and ``1.0``), which means the random value gravitates towards
the middle of whatever you're looking to randomize.

If we go back to trying to determine if a person should commit a crime
or not, then having a criminal history should sway the mean towards
1, increasing the likelihood of that outcome.

The ultimate purpose of extensions is to sway the mean in negative
or positive direction, thus affecting the final outcome.

### Creating an extension

Creating a basic extension is as easy as:

````php
class SimpleExtension implements Extension
{
    #[Expose]
    public function myFunc(Extendable $random, float $change): Extendable
    {
        return $random->changeMean($change);
    }
}
````

There are a few things to pay attention to.

- The class must implement ``Extension`` interface.
- Every method that must be accessible through the randomizer, must have the
  ``#[Expose]`` attribute.
- The first argument of an exposed method must be ``Extendable``. 
  This is the randomizer instance which will be injected.
- Exposed methods must return ``Extendable`` (i.e. the randomizer).

> You can explore ``ExtensionTest`` for a real-life example.

### Usage

When you want to use your extension, it must first get injected
into the randomizer. Once that's done, you have access to its 
exposed methods.

Here, we extend a ``RandomFloat`` with the amazing extension we
just built.

````php
$randomizer = new RandomFloat(50.0, 75.0);
$randomizer->extend(new SimpleExtension());
````

Now, you have access to the ``myFunc`` method:

````php
$randomizer->myFunc(0.3);
````

What happens in this particular example, is that the randomizer's
mean value (which starts at ``0.5``) is increased by ``0.3``,
 to ``0.8``.

When this mean is applied in the ``RandomFloat`` with min/max at
50 to 75, it means the most likely outcome is a number in the
higher end, probably around 70.

Notice a lot of "maybe" and "probably". This is because we use
biased randomization with standard distribution. No outcome
is completely guaranteed, which is exactly what we want: A tendency.

### Tip

Extensions can themselves take parameters. In our crime determination
example, we would for instance provide the person and maybe his/her
criminal history as constructor arguments.

## 💝 About the project

### Testing

You can run the test suite using:

````bash
composer test
````

### Linting

Linting is prepared for Laravel Pint. Point your IDE to use
the file ``pint.json``.
