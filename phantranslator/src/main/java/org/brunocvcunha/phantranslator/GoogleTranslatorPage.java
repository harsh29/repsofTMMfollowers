/**
 * Copyright (C) 2015 Bruno Candido Volpato da Cunha (brunocvcunha@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package org.brunocvcunha.phantranslator;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.CacheLookup;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.How;

/**
 * Simple mirror to Google Translator Panel
 * 
 * @author Bruno Candido Volpato da Cunha
 *
 */
public class GoogleTranslatorPage {

	@FindBy(how = How.ID_OR_NAME, using = "source")
	@CacheLookup
	private WebElement literal;

	public void insertLiteral(String literal) {
		this.literal.clear();
		this.literal.sendKeys(literal);
		// this.literal.submit();
	}
}
