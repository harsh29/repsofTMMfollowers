if (_CONTAINER_INK_) { exit }
_CONTAINER_INK_ = 1

import "general.ink"

__Array = Array
ExArray = fn (args...) {
	this = new __Array() with args
	this.indexOf = fn (a) {
		for (let i = 0, i < base.size(), i = i + 1) {
			if (base[i] == a) {
				retn i;
			}
		}
		-1;
	}
	this.`==` = fn (a) {
		if (a.size() != base.size()) {
			retn 0;
		}
		base.each() { | elem |
			if (a.indexOf(elem) == -1) {
				retn 0;
			}
		}
		1;
	}

	this.`*` = fn (a) {
		let ret = new Array();
		base.each() { | elem1 |
			a.each() { | elem2 |
				let tmp = new Array(elem1, elem2);
				if (ret.indexOf(tmp) == -1) {
					ret.push(tmp);
				}
			}
		}
		ret;
	}
	this.p = fn (args...) {
		prefix = ""
		if (args.size()) {
			prefix = args[0];
		}

		p(prefix + "{");
		base.each() { | elem |
			if (elem.p) {
				elem.p(prefix + "  ");
			} {
				p(prefix + "  " + elem + ",");
			}
		}
		p(prefix + "}");
	}
}
Array = ExArray;
cast_native_array = fn (narr) {
	ret = new Array();
	narr.each { | val |
		ret.push(val);
	}
	ret
}

Stack = fn () {
	this = new Array()

	this.pop = fn () {
		if (base.size() < 1) {
			retn null;
		}
		base.remove(base.size() - 1);
	}
}