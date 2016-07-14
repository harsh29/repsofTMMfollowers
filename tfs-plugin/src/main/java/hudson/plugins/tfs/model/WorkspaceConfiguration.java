package hudson.plugins.tfs.model;

import java.io.Serializable;

import hudson.model.InvisibleAction;

/**
 * An action for storing TFS configuration data in a build 
 * 
 * @author Erik Ramfelt, redsolo
 */
public class WorkspaceConfiguration extends InvisibleAction implements Serializable {

    private static final long serialVersionUID = 1L;
    
    private final String workspaceName;
    private final String workfolder;
    private final String projectPath;
    private final String proxyServer;
    private final String serverUrl;
    private boolean workspaceExists;

    public WorkspaceConfiguration(String serverUrl, String proxyServer, String workspaceName, String projectPath, String workfolder) {
        this.workspaceName = workspaceName;
        this.workfolder = workfolder;
        this.projectPath = projectPath;
        this.serverUrl = serverUrl;
        this.proxyServer = proxyServer;
        this.workspaceExists = true;
    }

    public WorkspaceConfiguration(WorkspaceConfiguration configuration) {
        this.workspaceName = configuration.workspaceName;
        this.workfolder = configuration.workfolder;
        this.projectPath = configuration.projectPath;
        this.serverUrl = configuration.serverUrl;
        this.proxyServer = configuration.proxyServer;
        this.workspaceExists = configuration.workspaceExists;
    }

    public String getWorkspaceName() {
        return workspaceName;
    }

    public String getWorkfolder() {
        return workfolder;
    }

    public String getProjectPath() {
        return projectPath;
    }

    public String getServerUrl() {
        return serverUrl;
    }
    
    public boolean workspaceExists() {
        return workspaceExists;
    }
    
    public void setWorkspaceWasRemoved() {
        this.workspaceExists = false;
    }

    public String getProxyServer() {
		return proxyServer;
	}

	@Override
    public int hashCode() {
        final int prime = 31;
        int result = 1;
        result = prime * result + ((projectPath == null) ? 0 : projectPath.hashCode());
        result = prime * result + ((serverUrl == null) ? 0 : serverUrl.hashCode());
        result = prime * result + ((proxyServer == null) ? 0 : proxyServer.hashCode());
        result = prime * result + ((workfolder == null) ? 0 : workfolder.hashCode());
        result = prime * result + (workspaceExists ? 1231 : 1237);
        result = prime * result + ((workspaceName == null) ? 0 : workspaceName.hashCode());
        return result;
    }

    @Override
    public boolean equals(Object obj) {
        if (this == obj)
            return true;
        if (obj == null)
            return false;
        if (!(obj instanceof WorkspaceConfiguration))
            return false;
        WorkspaceConfiguration other = (WorkspaceConfiguration) obj;
        if (projectPath == null) {
            if (other.projectPath != null)
                return false;
        } else if (!projectPath.equals(other.projectPath))
            return false;
        if (serverUrl == null) {
            if (other.serverUrl != null)
                return false;
        } else if (!serverUrl.equals(other.serverUrl))
            return false;
        if (proxyServer == null) {
        	if (other.proxyServer != null)
        		return false;
        } else if (!proxyServer.equals(other.proxyServer))
        	return false;
        if (workfolder == null) {
            if (other.workfolder != null)
                return false;
        } else if (!workfolder.equals(other.workfolder))
            return false;
        if (workspaceExists != other.workspaceExists)
            return false;
        if (workspaceName == null) {
            if (other.workspaceName != null)
                return false;
        } else if (!workspaceName.equals(other.workspaceName))
            return false;
        return true;
    }

    @Override
    public String toString() {
        return String.format("WorkspaceConfiguration [projectPath=%s, serverUrl=%s, proxyServer=%s, workfolder=%s, workspaceExists=%s, workspaceName=%s]", 
                projectPath, serverUrl, proxyServer, workfolder, workspaceExists, workspaceName);
    }    
}
