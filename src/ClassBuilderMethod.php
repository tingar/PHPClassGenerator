<?

/**
 * Method object, to be assigned to a class.
 *
 * @package PHPClassBuilder
 */
class ClassBuilderMethod extends ClassBuilderNode {
	protected $access = ClassBuilder::ACCESS_PUBLIC;
	protected $static = false;
	protected $code = '';
	protected $type = 'void';
	protected $args = array();

	/**
	 * Set the access permissions of this method.
	 *
	 * @param string $access
	 */
	function setAccess($access) {
		$this->access = $access;
		$this->setPHPDoc('access', $access);
	}

	/**
	 * Set this method to be a static method.
	 */
	function setStatic() {
		$this->static = true;
	}

	/**
	 * Set the return type of the method.
	 *
	 * @param string $type
	 */
	function setType($type) {
		$this->type = $type;
		$this->setPHPDoc('return', $type);
	}

	/**
	 * The code for the function itself.
	 *
	 * @param string $code
	 */
	function setCode($code) {
		$this->code = $code;
	}

	/**
	 * Add a formal argument to the function.
	 *
	 * @param string $type
	 * @param string $name
	 * @param mixed $default
	 * @param boolean $optional
	 */
	function addArg($type, $name, $default=null, $optional=null) {
		if (!array_key_exists($name, $this->args)) {
			$this->addPHPDoc('param', "$type $name");
		}
		$this->args[$name] = array(
			'type'         => $type,
			'defaultValue' => $default,
			'optional'     => $optional
		);
	}

	/**
	 * @return string
	 */
	function __toString() {
		$funcspec = $this->getComment();

		if (ClassBuilder::ACCESS_PUBLIC!=$this->access) {
			$funcspec .= "{$this->access} ";
		}
		if ($this->static) {
			$funcspec .= 'static ';
		}
		$funcspec .= "function {$this->name}(";
		$firstarg = true;
		foreach($this->args as $name => $arg) {
			if ($firstarg) {
				$firstarg = false;
			} else {
				$funcspec .= ', ';
			}
			// assume built-in types are all lowercase; classes aren't
			if (
				strtolower($arg['type'])!=$arg['type'] &&
				false==strstr($arg['type'], '[]') &&
				false==strstr($arg['type'], '|')
			) {
				$funcspec .= "{$arg['type']} ";
			}
			$funcspec .= $name;
		}
		$funcspec .= ") {\n";
		foreach(explode("\n", $this->code) as $line) {
			if ($line) {
				$funcspec .= "\t{$line}\n";
			} else {
				$funcspec .= "\n";
			}
		}
		$funcspec .= "}\n";

		return $funcspec;
	}
}
