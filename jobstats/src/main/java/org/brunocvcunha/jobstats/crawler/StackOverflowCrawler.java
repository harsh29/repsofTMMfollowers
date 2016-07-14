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
package org.brunocvcunha.jobstats.crawler;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URISyntaxException;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import lombok.extern.log4j.Log4j;

import org.brunocvcunha.inutils4j.MyDateUtils;
import org.brunocvcunha.inutils4j.MyStringUtils;
import org.brunocvcunha.jobstats.model.Position;
import org.brunocvcunha.jobstats.multiparser.JobPageParser;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

/**
 * Job Seeker implementation for career.stackoverflow.com
 * 
 * @author Bruno Candido Volpato da Cunha
 *
 */
@Log4j
public class StackOverflowCrawler implements IJobSeeker {

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.brunocvcunha.jobstats.crawler.IJobSeeker#getSeekerName()
	 */
	public String getSeekerName() {
		return "StackOverflow";
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.brunocvcunha.jobstats.crawler.IJobSeeker#fetch()
	 */
	@Override
	public List<Position> fetch() throws IOException {
		return fetch(null);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.brunocvcunha.jobstats.crawler.IJobSeeker#fetch(java.lang.String)
	 */
	@Override
	public List<Position> fetch(String keyword) throws IOException {
		return fetch(keyword, -1);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.brunocvcunha.jobstats.crawler.IJobSeeker#fetch(java.lang.String,
	 * int)
	 */
	@Override
	public List<Position> fetch(String keyword, int maximum) throws IOException {

		List<Position> found = new ArrayList<Position>();

		int page = 1;

		fetcher: while (true) {
			Document jobs = getDocumentFromPage(keyword, page++);
			log.info("Fetch page " + page + " (keyword: " + keyword + ") - "
					+ jobs.title());

			Elements jobElements = jobs.select("div[data-jobid]");
			if (jobElements.size() == 0) {
				break fetcher;
			}

			for (Element job : jobElements) {
				try {
					log.debug("Job HTML: " + job.html());

					found.add(getPositionFromDiv(job));

					if (maximum > 0 && found.size() >= maximum) {
						break fetcher;
					}

				} catch (Exception e) {
					log.error("Exception occurred while fetching job for "
							+ keyword + " at page " + page, e);
				}
			}
		}

		return found;
	}

	/**
	 * Parse job's div into a {@link Position}
	 * 
	 * @param job
	 * @return
	 * @throws IOException 
	 * @throws URISyntaxException 
	 */
	private Position getPositionFromDiv(Element job) throws URISyntaxException, IOException {

		Position pos = new Position();
		pos.setOrigin(getSeekerName());
		pos.setOriginId(job.select("a[data-jobid]").attr("data-jobid"));
		pos.setPostedDate(MyDateUtils.calculateAgo(new Date(),
				job.select("p.posted.top").text().trim()));
		pos.setJobTitle(job.select("a.job-link").text());

		StringBuffer sbJobUrl = new StringBuffer();
		sbJobUrl.append("http://careers.stackoverflow.com");
		sbJobUrl.append(job.select("a.job-link").attr("href"));

		pos.setOriginUrl(sbJobUrl.toString());

		parsePositionDetails(pos, sbJobUrl.toString());

		return pos;
	}

	/**
	 * Get position details (opens the job url)
	 * 
	 * @param pos
	 * @param jobUrl
	 * @throws IOException 
	 * @throws URISyntaxException 
	 */
	private void parsePositionDetails(Position pos, String jobUrl) throws URISyntaxException, IOException {
		log.info("Fetching URL " + jobUrl);
		JobPageParser.parseJobPage(pos, jobUrl);
	}

	/**
	 * Get Jsoup Document for the given keyword and page
	 * 
	 * @param keyword
	 *            Search Keyword
	 * @param page
	 *            Page to fetch
	 * @return Parsed HTML Document
	 * @throws UnsupportedEncodingException
	 */
	private Document getDocumentFromPage(String keyword, int page)
			throws UnsupportedEncodingException {
		StringBuffer sbUrl = new StringBuffer();
		sbUrl.append("http://careers.stackoverflow.com/jobs?sort=p");
		if (keyword != null) {
			sbUrl.append("&searchTerm=" + URLEncoder.encode(keyword, "UTF-8"));
		}

		if (page > 1) {
			sbUrl.append("&pg=" + page);
		}

		log.info("Fetching URL " + sbUrl.toString());
		
		String content = MyStringUtils.getContent(sbUrl.toString());
		return Jsoup.parse(content);

	}

}
