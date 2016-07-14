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

import org.apache.log4j.Logger;
import org.brunocvcunha.sockettester.vo.SocketTesterVO;

/**
 * AppServer Validator
 * 
 * @author Bruno Candido Volpato da Cunha
 *
 */
public class AppServerTesterService implements ISocketTesterService {

  private static Logger log = Logger.getLogger(AppServerTesterService.class);

  @Override
  public void validate(SocketTesterVO vo) {
    log.info("Validating AppServer " + vo);

    /*
     * Do nothing at this time Connection conn = null; AppObject obj = null; try { String
     * connectionURL = "AppServer://" + vo.getHost() + ":" + vo.getPort() + "/" + vo.getService();
     * conn = new Connection(connectionURL, "", "", ""); obj = new AppObject("emsflex", conn,
     * RunTimeProperties.tracer, null);
     * 
     * ParameterSet params = new ParameterSet(0); Procedure opo = obj.CreatePO("java/ping.p",
     * params);
     * 
     * obj.getSession().isConnected();
     * 
     * vo.setValid(true);
     * 
     * // System.out.println("Connection with " + connectionURL + " - " + validApp); } catch
     * (Exception e) { e.printStackTrace();
     * 
     * vo.setValid(false); vo.setStatus("Falhou: " + e.getMessage());
     * 
     * } finally { try { obj.getSession().shutdown(); } catch (Exception e) { }
     * 
     * try { obj._cancelAllRequests(); } catch (Exception e) { } try { obj._release(); } catch
     * (Exception e) { } try { conn.releaseConnection(); } catch (Exception e) { } try {
     * conn.finalize(); } catch (Exception e) { } }
     */
  }

}
