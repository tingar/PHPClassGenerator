<?

class ClassBuilderMethod extends ClassBuilderNode {
	protected $access = ClassBuilder::ACCESS_PUBLIC;
	protected $static = false;
	protected $code = '';
	protected $type = 'void';
	protected $args = array();

	function setAccess($access) {
		$this->access = $access;

		$documented = false;
		foreach($this->phpdoc as $doc) {
			if ('access'==$doc['type']) {
				$doc['value'] = $access;
				$documented = true;
			}
		}
		if (!$documented) {
			$this->addPHPDoc('access', $access);
		}
	}

	function addArg($type, $name, $default=null, $optional=null) {
		if (!array_key_exists($name, $this->args)) {
			$this->addPHPDoc('param', '$type $name');
		}
		$this->args[$name] = array($type, $default, $optional);
	}

	function __toString() {
		$s = $this->getComment();

		$funcspec = '';
		if (ClassBuilder::ACCESS_PUBLIC!=$access) {
			$funcspec .= "$access ";
		}
		if ($this->static) {
			$funcspec .= 'static ';
		}
		$funcspec .= "function {$this->name}(";
		$firstarg = true;
		foreach($this->args as $arg) {
			if ($firstarg) {
				$firstarg = false;
			} else {
				$funcspec .= ', ';
			}
			// assume built-in types are all lowercase; classes aren't
			if (strtolower($type)!=$type) {
				$funcspec .= "$type ";
			}
			$funcspec .= "$name";
		}
		$funcspec .= ") {\n{$this->code}\n}\n";

		return $funcspec;
	}
}
