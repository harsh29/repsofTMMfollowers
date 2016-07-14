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
package org.brunocvcunha.sockettester;

import java.io.IOException;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

import org.brunocvcunha.sockettester.core.SocketTesterController;
import org.brunocvcunha.sockettester.vo.SocketTesterVO;
import org.dom4j.Element;
import org.dom4j.io.SAXReader;
import org.dom4j.tree.DefaultElement;
import org.omg.CORBA.BooleanHolder;

/**
 * Main Class, that reads sockettester-sample.xml and tests all environments
 * 
 * @author Bruno Candido Volpato da Cunha
 *
 */
public class SocketTesterMain {
  public static void main(String[] args) throws Exception {

    Element root =
        new SAXReader()
            .read(SocketTesterMain.class.getResourceAsStream("/sockettester-sample.xml"))
            .getRootElement();

    final BooleanHolder hasError = new BooleanHolder(false);

    final ExecutorService service = Executors.newFixedThreadPool(10);

    @SuppressWarnings("unchecked")
    List<DefaultElement> ambientes = root.elements("environment");

    for (final DefaultElement env : ambientes) {
      Thread t1 = new Thread() {
        public void run() {
          SocketTesterVO vo;
          try {
            vo = SocketTesterController.validateEnvironmentNode(env);
            if (!vo.getStatus().equals("OK")) {
              hasError.value = true;
              System.err.println(vo.getHost() + ":" + vo.getPort() + " (" + vo.getName() + "): "
                  + vo.getStatus());
            } else {
              System.out.println(vo.getHost() + ":" + vo.getPort() + " (" + vo.getName() + "): "
                  + vo.getStatus());
            }
          } catch (IOException e) {
            e.printStackTrace();
          }

        }
      };
      service.submit(t1);
    }

    service.shutdown();
    service.awaitTermination(1, TimeUnit.MINUTES);

    if (hasError.value) {
      System.err.println("Errors occurred in SocketTester.");
    }

  }
}
