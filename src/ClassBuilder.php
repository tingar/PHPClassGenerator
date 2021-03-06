<?

/**
 * Builds a class, which can be exported as code with the {@link
 * __toString()} function.
 *
 * @package PHPClassBuilder
 */
class ClassBuilder extends ClassBuilderNode {

	const ACCESS_PUBLIC = 'public';
	const ACCESS_PROTECTED = 'protected';
	const ACCESS_PRIVATE = 'private';

	/**
	 * @var
	 * @access protected
	 */
	protected $_constants = array();

	/**
	 * @var
	 * @access protected
	 */
	protected $_fields = array();

	/**
	 * @var
	 * @access protected
	 */
	protected $_methods = array();

	/**
	 * @var
	 * @access protected
	 */
	protected $_abstract = false;

	/**
	 * @var
	 * @access protected
	 */
	protected $_final = false;

	/**
	 * @var
	 * @access protected
	 */
	protected $_superclass = null;

	/**
	 * @var
	 * @access protected
	 */
	protected $_interfaces = array();

	/**
	 * @param string $name
	 * @param string $value
	 */
	function addConstant($name, $value) {
		$this->_constants[$name] = $value;
	}

	/**
	 * @param ClassBuilderField $field
	 */
	function addField(ClassBuilderField $field) {
		$this->_fields[$field->getName()] = $field;
	}

	/**
	 * @param ClassBuilderMethod $method
	 */
	function addMethod(ClassBuilderMethod $method) {
		$this->_methods[$method->getName()] = $method;
	}

	/**
	 * @param string $package
	 */
	function setPackage($package) {
		$this->setPHPDoc('package', $package);
	}

	/**
	 * @param string $superclass
	 */
	function setSuperClass($superclass) {
		$this->_superclass = $superclass;
	}

	/**
	 * @return string
	 */
	function __toString() {
		$classSpec = $this->getComment();

		if ($this->_abstract) {
			$classSpec .= 'abstract ';
		}

		$classSpec .= "class {$this->getName()}";
		if ($this->_superclass) {
			$classSpec .= " extends {$this->_superclass}";
		}
		foreach($this->_interfaces as $interface) {
			$classSpec .= "\n\timplements $interface";
		}

		$classSpec .= ($this->_interfaces ? "\n" : " ")."{\n\n";

		foreach($this->_constants as $name => $value) {
			if (!ctype_digit($value)) {
				$value = "'{$value}'";
			}
			$classSpec .= "\tconst {$name} = {$value};\n";
		}
		if ($this->_constants) $classSpec .= "\n";

		foreach($this->_fields as $field) {
			foreach(explode("\n", $field->__toString()) as $line) {
				if ($line) {
					$classSpec .= "\t{$line}\n";
				} else {
					$classSpec .= "\n";
				}
			}
		}

		foreach($this->_methods as $method) {
			foreach(explode("\n", $method->__toString()) as $line) {
				if ($line) {
					$classSpec .= "\t{$line}\n";
				} else {
					$classSpec .= "\n";
				}
			}
		}

		return $classSpec . "}\n";
	}

}
