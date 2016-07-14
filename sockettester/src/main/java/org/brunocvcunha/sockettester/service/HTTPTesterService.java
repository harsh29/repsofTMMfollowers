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

import java.net.HttpURLConnection;
import java.net.URL;

import org.apache.log4j.Logger;
import org.brunocvcunha.inutils4j.MyStringUtils;
import org.brunocvcunha.sockettester.vo.SocketTesterVO;

/**
 * HTTP Validator
 * 
 * @author Bruno Candido Volpato da Cunha
 *
 */
public class HTTPTesterService implements ISocketTesterService {

  private static Logger log = Logger.getLogger(HTTPTesterService.class);

  @Override
  public void validate(SocketTesterVO vo) {
    log.info("Validating HTTP " + vo);

    boolean validHttp = true;

    try {
      String stringUrl =
          "http://" + vo.getHost() + ":" + vo.getPort() + "/"
              + (vo.getService() != null ? vo.getService() : "");
      URL url = new URL(stringUrl);
      HttpURLConnection conn = (HttpURLConnection) url.openConnection();

      String contentResponse = MyStringUtils.getContent(conn.getInputStream());
      log.debug("Response: " + contentResponse);

      if (conn.getResponseCode() != HttpURLConnection.HTTP_OK) {
        log.info("Response from " + stringUrl + ": " + conn.getResponseCode() + " "
            + conn.getResponseMessage());
      } else {
        log.debug("Response from " + stringUrl + ": " + conn.getResponseCode() + " "
            + conn.getResponseMessage());
      }

    } catch (Exception e) {
      e.printStackTrace();
      validHttp = false;
    }

    vo.setValid(validHttp);
    if (!validHttp) {
      vo.setStatus("HTTPRequest Failed");
    }
  }

}
