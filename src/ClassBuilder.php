<?

class ClassBuilder extends ClassBuilderNode {
	const ACCESS_PUBLIC    = 'public';
	const ACCESS_PROTECTED = 'protected';
	const ACCESS_PRIVATE   = 'private';

	protected $_fields  = array();
	protected $_methods = array();

	protected $_abstract   = false;
	protected $_final      = false;
	protected $_superclass = null;
	protected $_interfaces = array();

	public function addField(ClassBuilderField $field) {
		$this->_fields[$field->getName()] = $field;
	}

	public function addMethod(ClassBuilderMethod $method) {
		$this->_methods[$method->getName()] = $method;
	}

	public function __toString() {
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

		foreach($this->_fields as $field) {
			foreach(explode("\n", $field->__toString()) as $line) {
				$classSpec .= "\t{$line}\n";
			}
		}
		foreach($this->_methods as $method) {
			foreach(explode("\n", $method->__toString()) as $line) {
				$classSpec .= "\t{$line}\n";
			}
		}

		return $classSpec . "\n}\n";
	}
}