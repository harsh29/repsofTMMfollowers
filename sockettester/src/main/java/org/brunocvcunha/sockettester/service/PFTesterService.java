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
package org.brunocvcunha.sockettester.service;

import jargs.gnu.CmdLineParser;

import java.io.File;
import java.io.IOException;

import org.apache.log4j.Logger;
import org.brunocvcunha.inutils4j.MyStringUtils;
import org.brunocvcunha.sockettester.core.SocketTesterHelper;
import org.brunocvcunha.sockettester.vo.SocketTesterVO;

/**
 * PF Validator
 * 
 * @author Bruno Candido Volpato da Cunha
 *
 */
public class PFTesterService implements ISocketTesterService {

  private static Logger log = Logger.getLogger(PFTesterService.class);

  @Override
  public void validate(SocketTesterVO vo) throws IOException {
    log.info("Validating PF " + vo);

    vo.setValid(true);

    CmdLineParser parser = new CmdLineParser();
    CmdLineParser.Option host = parser.addStringOption('H', "host");
    CmdLineParser.Option port = parser.addIntegerOption('S', "port");
    CmdLineParser.Option type = parser.addStringOption('N', "type");
    CmdLineParser.Option db = parser.addStringOption('d', "db");
    // CmdLineParser.Option logic = parser.addStringOption('l', "ld");

    File pfFile = new File(vo.getService());
    if (pfFile.exists()) {
      String content = MyStringUtils.getContent(pfFile);
      content = content.replace("\r\n", "\n");

      String[] linhas = content.split("\n");

      for (String linha : linhas) {
        if (linha.startsWith("-db")) {
          linha = linha.replace("-db", "-d");

          try {
            parser.parse(linha.split("\\s"));
          } catch (CmdLineParser.OptionException e) {
            // System.err.println(e.getMessage());
          }

          String dbStr = (String) parser.getOptionValue(db);
          String typeStr = (String) parser.getOptionValue(type);
          String hostStr = (String) parser.getOptionValue(host);
          Integer portInt = (Integer) parser.getOptionValue(port);

          if ("tcp".equalsIgnoreCase(typeStr)) {
            boolean validDb = SocketTesterHelper.isValidSocket(hostStr, portInt);
            if (!validDb) {
              vo.setValid(false);
              vo.setStatus("Database " + dbStr + " not reachable");
              return;
            }
          }
        }
      }

    } else {
      vo.setValid(false);
      vo.setStatus("PF File " + vo.getService() + " not found.");
    }

  }
}
