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
package org.brunocvcunha.sockettester.core;

import java.io.IOException;

import org.apache.log4j.Logger;
import org.brunocvcunha.sockettester.service.ISocketTesterService;
import org.brunocvcunha.sockettester.vo.SocketTesterVO;
import org.dom4j.Element;

/**
 * Core tester
 * 
 * @author Bruno Candido Volpato da Cunha
 * 
 */
public class SocketTesterController {

  private static Logger log = Logger.getLogger(SocketTesterController.class);


  public static SocketTesterVO validateEnvironmentNode(Element env) throws IOException {
    log.info("Validating environment node...");

    SocketTesterVO vo = new SocketTesterVO();
    vo.setType(env.elementText("type"));
    vo.setName(env.elementText("description"));
    vo.setHost(env.elementText("host"));
    vo.setPort(Integer.valueOf(env.elementText("port")));
    vo.setValid(SocketTesterHelper.isValidSocket(vo.getHost(), vo.getPort()));
    vo.setService(env.elementText("service"));

    if (vo.isValid()) {
      vo.setStatus("OK");
      vo.setValid(true);

      validate(vo);

    } else {
      vo.setStatus("Error establishing connection.");
    }

    return vo;
  }

  public static void validate(SocketTesterVO vo) {
    try {
      @SuppressWarnings("unchecked")
      Class<ISocketTesterService> serviceClass =
          (Class<ISocketTesterService>) Class.forName("org.brunocvcunha.sockettester.service."
              + vo.getType() + "TesterService");
      ISocketTesterService service = serviceClass.newInstance();

      service.validate(vo);
    } catch (Exception e) {
      throw new RuntimeException("Error occurred validating socket " + vo, e);
    }
  }
}
