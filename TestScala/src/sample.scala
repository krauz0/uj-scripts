class Compass {
  //konstruktor
  val directions = List("north", "east", "south", "west")
  var bearing = 0
  print("Initial bearing: ")
  println(direction)

  def direction() = directions(bearing)

  def inform(turnDirection: String) {
    println("Turning " + turnDirection + ". Now bearing " + direction)
  }

  def turnRight() {
    bearing = (bearing + 1) % directions.size
    inform("right")
  }

  def turnLeft() {
    bearing = (bearing + (directions.size - 1)) % directions.size
    inform("left")
  }
}

val myCompass = new Compass

myCompass.turnRight
myCompass.turnRight

myCompass.turnLeft
myCompass.turnLeft
myCompass.turnLeft

class Person(firstName: String) {
  println("Outer constructor")

  def this(firstName: String, lastName: String) {
    this(firstName)
    println("Inner constructor")
  }
}

val bob = new Person("Bob")
val bobTate = new Person("Bob", "Tate")

class Berson (val name:String)

trait ABCD {
  def sample() = println("Sample")
}

trait Nice {
  def greet = println("Howdily doodily.")
}

class Character(override val name: String) extends Berson(name) with ABCD with Nice

val myCharacter: Character = new Character("andy")
myCharacter.greet
myCharacter.sample

var a = List(1, 2, 3)
var b = List(1, 2, 3)

if (a == b) {
  println("True")
} else {
  println("False")
}

var set1 = Set("a", "b", "c");
var set2 = Set("b", "c")

println(set1 -- set2)